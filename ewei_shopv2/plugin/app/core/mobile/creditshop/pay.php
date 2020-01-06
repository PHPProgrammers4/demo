<?php

/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class Pay_EweiShopV2Page extends AppMobilePage
{
    public function lottery(){
        global $_W, $_GPC;

        $number = max(1,$_GPC['num']);
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $open_redis = function_exists('redis') && !is_error(redis());
        if( $open_redis ) {
            $redis_key = "{$_W['setting']['site']['key']}_{$_W['account']['key']}_{$uniacid}_creditshop_lottery_{$openid}";
            $redis = redis();
            if (!is_error($redis)) {
                if ($redis->setnx($redis_key, time())) {
                    $redis->expireAt($redis_key, time() + 2);
                } else {
                    show_json(0,array('status'=>'-1','message'=>'操作频繁，请稍后再试!'));
                }
            }
        }

        $logid = intval($_GPC['logid']);

        $shop = m('common')->getSysset('shop');
        $member = m('member')->getMember($openid);
        $goodsid = intval($_GPC['goodsid']);

        $log = pdo_fetch('select * from ' . tablename('ewei_shop_creditshop_log') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $logid, ':uniacid' => $uniacid));

        if(empty($log)){
            $logno=$_GPC['logno'];
            $log = pdo_fetch('select * from ' . tablename('ewei_shop_creditshop_log') . ' where logno=:logno and uniacid=:uniacid limit 1', array(':logno' => $logno, ':uniacid' => $uniacid));
        }


        $optionid = $log['optionid'];
        $goods = p('creditshop')->getGoods($log['goodsid'], $member,$log['optionid'],$number);
        $goods['money'] *= $number;
        $goods['credit'] *= $number;
        $goods['dispatch'] = p('creditshop')->dispatchPrice($log['goodsid'],$log['addressid'],$log['optionid'],$number);
        $credit = $member['credit1'];
        $money = $member['credit2'];
        if (empty($log)) {
            show_json(0,array('status'=>'-1','message'=>'服务器错误!'));
        }
//        if ($log['status']>=1) {
//            show_json(0,array('status'=>'-1','message'=>'支付成功!'));
//        }

        if (empty($goods['canbuy'])) {
            show_json(0,array('status'=>'-1','message'=>$goods['buymsg']));
        }
        $update = array('couponid'=>$goods['couponid']);

        if (empty($log['paystatus'])){
            if ($goods['credit']>0 && $credit<$goods['credit']) {
                show_json(0,array('status'=>'-1','message'=>'积分不足!'));
            }
            if ($goods['money'] > 0 && $money<$goods['money'] && $log['paytype'] == 0) {
                show_json(0,array('status'=>'-1','message'=>'余额不足!'));
            }
        }
        $update['money'] = $goods['money'];

        //支付状态
        if (($goods['money'] + $goods['dispatch']) > 0 && $log['paystatus']<1) {
            if ($log['paytype'] == 0) {
                //余额支付
                m('member')->setCredit($openid, 'credit2', -($goods['money'] + $goods['dispatch']), "积分商城扣除余额度 ".($goods['money'] + $goods['dispatch']));
                $update['paystatus']  = 1;
            }

            if ($log['paytype'] == 1){
                $payquery = m('finance')->isWeixinPay($log['logno'],($goods['money'] + $goods['dispatch']), is_h5app()?true:false);

                $payqueryBorrow = m('finance')->isWeixinPayBorrow($log['logno'],($goods['money'] + $goods['dispatch']));
                if (!is_error($payquery) || !is_error($payqueryBorrow)) {
                    //微信支付
                    p('creditshop')->payResult($log['logno'], 'wechat',($goods['money'] + $goods['dispatch']), is_h5app()?true:false);

                }else{
                    show_json(0,array('status'=>'-1','message'=>'支付出错,请重试(1)!'));
                }
            }
            if ($log['paytype'] == 2){
                if ($log['paystatus']<1){
                    show_json(0,array('status'=>'-1','message'=>'未支付成功!'));
                }
            }

            //支付状态
        }

        if($goods['credit']>0 && empty($log['creditpay'])){
            $update['credit'] = $goods['credit'];
            //扣除积分
            m('member')->setCredit($openid, 'credit1', -$goods['credit'], "积分商城扣除积分 {$goods['credit']}");
            $update['creditpay'] = 1;
            //参加次数
            pdo_query('update '.tablename('ewei_shop_creditshop_goods').' set joins=joins+1 where id='.$log['goodsid']);
        }

        $status = 1;

        if ($goods['type']==1) {
            if ($goods['rate1'] > 0 && $goods['rate2'] > 0) {
                if ($goods['rate1'] == $goods['rate2']) {
                    //永远中奖
                    $status = 2;
                } else {
                    $rand = rand(0, intval($goods['rate2']));
                    if ($rand <= intval($goods['rate1'])) {
                        //中奖
                        $status = 2;
                    }
                }
            }
        }else{
            $status=2;
        }
        //核销生成核销码
        if ($status == 2 && $goods['isverify']==1) {
            $update['eno'] = p('creditshop')->createENO();
        }
        //核销限制时间，核销次数
        if($goods['isverify'] == 1){
            $update['verifynum'] = $goods['verifynum']>0 ? $goods['verifynum'] : 1;
            if($goods['isendtime']==0){
                if($goods['usetime'] > 0){
                    $update['verifytime'] = time() + 3600*24*intval($goods['usetime']);
                }else{
                    $update['verifytime'] = 0;
                }
            }else{
                $update['verifytime'] = intval($goods['endtime']);
            }
        }

        $update['status'] =  $status;
        if($goods['dispatch']>0 && $goods['goodstype']==0 && $goods['type'] == 0){
            $update['dispatchstatus'] = '1';
            $update['dispatch'] = $goods['dispatch'];
        }
        pdo_update('ewei_shop_creditshop_log', $update, array('id' => $log['id']));

        $log = pdo_fetch('select * from ' . tablename('ewei_shop_creditshop_log') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $logid, ':uniacid' => $uniacid));
        if($status==2 && $update['creditpay'] == 1){
            if($goods['goodstype']==1){
                //如果是优惠券
                if(com('coupon')){
                    com('coupon')->creditshop($logid);
                    $status = 3;
                }
                $update['time_finish'] = time();
            }elseif($goods['goodstype']==2){
                $credittype = "credit2";
                $creditstr = "积分商城兑换余额";
                $num = abs($goods['grant1'])*intval($log['goods_num']);
                $member = m('member')->getMember($openid);
                $credit2 = floatval($member['credit2']) + $num;
                m('member')->setCredit($openid, $credittype, $num, array($_W['uid'], $creditstr));

                $set = m('common')->getSysset('shop');
                $logno = m('common')->createNO('member_log', 'logno', 'RC');
                $data = array(
                    'openid' => $openid,
                    'logno' => $logno,
                    'uniacid' => $_W['uniacid'],
                    'type' => '0',
                    'createtime' => TIMESTAMP,
                    'status' => '1',
                    'title' => $set['name'] . "积分商城兑换余额",
                    'money' => $num,
                    'remark' => $creditstr,
                    'rechargetype' => 'creditshop'
                );
                pdo_insert('ewei_shop_member_log', $data);
                $mlogid = pdo_insertid();
                m('notice')->sendMemberLogMessage($mlogid);
                plog('finance.recharge.' . $credittype, "充值{$creditstr}: {$num} <br/>会员信息: ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
                $status = 3;
                $update['time_finish'] = time();
            }elseif($goods['goodstype']==3){


                /*$money = abs($goods['grant2']);
                $setting = uni_setting($_W['uniacid'], array('payment'));
                if (!is_array($setting['payment'])) {
                    return error(1, '没有设定支付参数');
                }
                $sec = m('common')->getSec();
                $sec = iunserializer($sec['sec']);
                $certs = $sec;
                $wechat = $setting['payment']['wechat'];
                $sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid limit 1';
                $row = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));

                //红包参数
                $params = array(
                    'openid'=>$openid,
                    'tid'=>$log['logno'],
                    'send_name'=>'积分商城红包兑换',
                    'money'=>$money,
                    'wishing'=>'红包领到手抽筋，别人加班你加薪!',
                    'act_name'=>'积分商城红包兑换',
                    'remark'=>'积分商城红包兑换',
                );
                //微信接口参数
                $wechat = array(
                    'appid' => $row['key'],
                    'mchid' => $wechat['mchid'],
                    'apikey' => $wechat['apikey'],
                    'certs' => $certs
                );
                $err = m('common')->sendredpack($params,$wechat);
                if(is_error($err)){
                    show_json(-1,array('status'=>-1,'message'=>'红包发放出错，请联系管理员!'));
                }else{
                    $status = 3;
                    $update['time_finish'] = time();
                }*/
            }
            $update['status'] =  $status;
            pdo_update('ewei_shop_creditshop_log', $update, array('id' => $logid));
            //模板消息
            p('creditshop')->sendMessage($logid);
            if($status == 3){
                //修改库存
                pdo_query('update '.tablename('ewei_shop_creditshop_goods').' set total=total-'.$number.' where id='.$log['goodsid']);
            }
            if($goods['goodstype']==0 && $status == 2){
                //实体商品修改库存
                pdo_query('update '.tablename('ewei_shop_creditshop_goods').' set total=total-'.$number.' where id='.$log['goodsid']);
            }
            //红包修改数量
            if($goods['goodstype']==3 && $status == 2){
                pdo_query('update '.tablename('ewei_shop_creditshop_goods').' set packetsurplus=packetsurplus-'.$number.' where id='.$log['goodsid']);
            }
            //是否有规格
            if($goods['hasoption'] && $log['optionid']){
                //规格商品修改库存
                pdo_query('update '.tablename('ewei_shop_creditshop_option').' set total=total-'.$number.' where id='.$log['optionid']);
            }
        }
        show_json(1,array('status'=>$status,'goodstype'=>$goods['goodstype']));
    }
}