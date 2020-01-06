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

class Index_EweiShopV2Page extends AppMobilePage
{
    // 获取 会员卡列表
    public function getlist()
    {
        global $_W, $_GPC;

        $openid = $_W['openid'];
        $cate = trim($_GPC['cate']);
        $cate = empty($cate)?'all':$cate;
        $plugin_membercard = p('membercard');
        if(!$plugin_membercard){
            return app_error(AppError::$PluginNotFound);
        }
        if ($cate == 'my') {
            //$openid = 'sns_wa_oI33v0ChPp5vebvFgwrDepni0sDI';
            $all = $plugin_membercard->get_Mycard($openid,$_GPC['page'], 20, 'all');
            $list = $all['list'];
            $psize = $all['psize'];
            $total = $all['total'];

            //总的会员卡
            $condition = ' uniacid = :uniacid ';
            $params = array(':uniacid' => $_W['uniacid']);
            $condition .= ' and status=1 and isdelete=0';
            $all_total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('ewei_shop_member_card') . "where {$condition}", $params);
            $my_total = $all['total'];
        } else {
            $all = $plugin_membercard->get_Allcard($_GPC['page']);
            $list = $all['list'];
            $psize = $all['psize'];
            $total = $all['total'];

            //我的所有会员卡
            $card_condition = "openid =:openid and uniacid=:uniacid and isdelete=0";
            $params = array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);
            $now_time = TIMESTAMP;
            $card_condition .= " and (expire_time=-1 or expire_time>{$now_time})";
            $my_total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('ewei_shop_member_card_history') . " 
				WHERE {$card_condition} limit 1", $params);
            $all_total = $all['total'];
        }

        return app_json(array('list' => $list, 'pagesize' => $psize, 'total' => $total,'my_total'=>$my_total,'all_total'=>$all_total));
    }

    // 获取 会员卡列表
    public function detail(){
        global $_W, $_GPC;

        $id = $_GPC['id'];
        $cate = trim($_GPC['cate']);
        $cate = empty($cate)?'all':$cate;
        $openid = $_W['openid'];
        $plugin_membercard = p('membercard');
        if(!$plugin_membercard){
            return app_error(AppError::$PluginNotFound);
        }

        if ($cate == 'my') {
            //$openid = 'sns_wa_oI33v0ChPp5vebvFgwrDepni0sDI';
            $all = $plugin_membercard->get_Mycard($openid,$_GPC['page'], 20,'all');
            $list = $all['list'];
            $psize = $all['psize'];
            $total = $all['total'];
        } else {
            $all = $plugin_membercard->get_Allcard($_GPC['page']);
            $list = $all['list'];
            $psize = $all['psize'];
            $total = $all['total'];
        }

        if(!empty($list)){
            foreach ($list as $key => $val){
                $card = $val;
                $rightsnum = 0;
                //if($card['discount']) $rightsnum += 1;
                if($card['shipping']) $rightsnum += 1;
                if($card['member_discount']) $rightsnum += 1;
                $condition = " and uniacid=:uniacid and openid =:openid and member_card_id = :cardid  ";
                $params = array(':uniacid' => $_W['uniacid'],':openid'=>$openid,':cardid'=>$card['id']);
                //开卡赠送积分
                if($card['is_card_points']){
                    $rightsnum += 1;
                    $send_point = false;
                    $buysend_point = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_member_card_buysend') . " 
				WHERE sendtype=1 {$condition}", $params);
                    if($buysend_point){
                        $send_point = true;
                    }
                    $card['send_point'] = $send_point;
                }

                //开卡赠送优惠券
                if($card['is_card_coupon']){
                    $rightsnum += 1;
                    $card_coupon = iunserializer($card['card_coupon']);
                    $card_coupons = array();
                    if($card_coupon['couponids']){
                        $card_coupons = $plugin_membercard->querycoupon($card_coupon['couponids']);
                        foreach ($card_coupons as $key1 => $val1){
                            $send_coupon = false;
                            $condition .= " and card_couponid = :card_couponid ";
                            $params[':card_couponid'] = $val1['id'];
                            $buysend_coupon = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_member_card_buysend') . " 
				WHERE sendtype=2 {$condition}", $params);
                            if($buysend_coupon){
                                $send_coupon = true;
                            }
                            $card_coupons[$key1]['send_coupon'] = $send_coupon;
                            $send_coupon_num = 1;
                            if($card_coupon['paycpnum'.($key1+1)]){
                                $send_coupon_num = $card_coupon['paycpnum'.($key1+1)];
                            }
                            $card_coupons[$key1]['send_coupon_num'] = $send_coupon_num;
                        }
                    }
                    $card['card_coupon'] = $card_coupons;
                }else{
                    unset($card['card_coupon']);
                }

                //每月领取积分
                if($card['is_month_points']){
                    $rightsnum += 1;
                    $isget_month_point = false;
                    if($plugin_membercard->check_month_point($card['id'],$openid)){
                        $isget_month_point = true;      //已经领取
                    }
                    $card['isget_month_point'] = $isget_month_point;
                }

                //每月送优惠券
                if($card['is_month_coupon']){
                    $rightsnum += 1;
                    $month_coupon = iunserializer($card['month_coupon']);
                    $month_coupons = array();
                    if($month_coupon['couponid']){
                        $month_coupons = $plugin_membercard->querycoupon($month_coupon['couponid']);
                        foreach ($month_coupons as $key2 => $val2){
                            $isget_month_coupon = false;
                            if($plugin_membercard->check_month_coupon($card['id'],$openid,$val2['id'])){
                                $isget_month_coupon = true;   //已经领取
                            }
                            $month_coupons[$key2]['isget_month_coupon'] = $isget_month_coupon;
                            $month_coupon_num = 1;
                            if($month_coupon['paycpnum'.($key2+1)]){
                                $month_coupon_num = $month_coupon['paycpnum'.($key2+1)];
                            }
                            $month_coupons[$key2]['month_coupon_num'] = $month_coupon_num;
                        }
                    }
                    $card['month_coupon'] = $month_coupons;
                }else{
                    unset($card['month_coupon']);
                }
                $card_validate = $card['validate'];
                $card['rightsnum'] = $rightsnum;
                $card['card_validate'] = str_replace("有效期:","",$card_validate);
                $list[$key] = $card;
                unset($card);
            }
        }

       /* if(empty($id)){
            return app_error(AppError::$ParamsError);
        }
        $card = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_member_card') . " 
				WHERE id =:id and uniacid=:uniacid and isdelete=0 limit 1", array(':uniacid' => $_W['uniacid'], ':id' => $id));
        if(empty($card)){
            return app_error(AppError::$CardNotFund);
        }*/

        /*$has_flag = $this->check_Hasget($id,$openid);
        if($has_flag['errno']==1 || ($has_flag['errno']==0 && $has_flag['using']=='-1')){
            //未领取过 或者 领取过 已经过期
            if($card['validate'] == '-1'){
                $card['validate'] = '永久有效';
            }else{
                $card['validate'] = '有效期:'.$card['validate'].'个月';
            }
            $card['startbuy'] = 1;        //显示立即开通
        }else{
            //领取过正在使用中
            $card['validate'] = $has_flag['validate'];
            if($has_flag['using']==1){
                $card['startbuy'] = 0;    //显示续费
            }else{
                $card['startbuy'] = -1;   //已经购买无需续费
            }
        }*/

        return app_json(array('list' => $list, 'pagesize' => $psize, 'total' => $total,'currentid'=>$id));
        //app_json(array('card' => $card));
    }

    //每月领取积分
    public function get_month_point(){
        global $_W, $_GPC;

        $cardid = $_GPC['id'];              //会员卡ID
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        if(empty($openid) || empty($cardid)){
            return app_error( AppError::$ParamsError );
        }
        $plugin_membercard = p('membercard');
        if(!$plugin_membercard){
            return app_error(AppError::$PluginNotFound);
        }
        $card = $plugin_membercard->getMemberCard($cardid);
        if(empty($card)){
            return app_error(AppError::$CardNotFund);
        }
        if($card['isdelete']){
            return app_error(AppError::$CardisDel);
        }

        $has_flag = $plugin_membercard->check_Hasget($cardid,$openid);
        if($has_flag['errno'] > 0){
            //没购买会员卡
            return app_error(82034,$has_flag['msg']);
        }else{
            //会员卡已过期
            if($has_flag['using']=='-1'){
                return app_error(AppError::$CardisOverTime);
            }
        }

        //判断是否本月领取过
        if($plugin_membercard->check_month_point($cardid,$openid)){
            return app_error(82033,'本月已领取过');
        }

        if(!$card['is_month_points']){
            return app_error(82034,'此会员卡没有此项福利');
        }
        $month_points = $card['month_points'];
        if($month_points <= 0){
            return app_error(82035,'会员卡数据有误');
        }

        //赠送积分并且生成领取记录
        $result = m('member')->setCredit($openid, 'credit1', $month_points, array($_W['member']['uid'], '购买会员卡'.$card['name'].date('m').'月领取'. $month_points.'积分'));
        if (is_error($result)) {
            return app_error(82035,$result['message']);
        }
        $send_log = array(
            'uniacid'           => $uniacid,
            'openid'            => $openid,
            'member_card_id'   => $cardid,
            'name'              => $card['name'],
            'receive_time'     => TIMESTAMP,
            'create_time'      => TIMESTAMP,
            'price'             => $card['price'],
            'validate'          => $card['validate'],
            'sendtype'          => 1,
            'card_points'       => $month_points
        );
        $recid = pdo_insert('ewei_shop_member_card_monthsend', $send_log);
        if(!$recid){
            return app_error(AppError::$SystemError);
        }
        return app_json(array('data'=>$send_log));
    }

    //每月领取优惠券
    public function get_month_coupon(){
        global $_W, $_GPC;

        $cardid = $_GPC['id'];              //会员卡ID
        $couponid = $_GPC['couponid'];      //优惠券ID
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        if(empty($openid) || empty($cardid) || empty($couponid)){
            return app_error( AppError::$ParamsError );
        }
        $plugin_membercard = p('membercard');
        if(!$plugin_membercard){
            return app_error(AppError::$PluginNotFound);
        }
        $card = $plugin_membercard->getMemberCard($cardid);
        if(empty($card)){
            return app_error(AppError::$CardNotFund);
        }
        if($card['isdelete']){
            return app_error(AppError::$CardisDel);
        }

        $has_flag = $plugin_membercard->check_Hasget($cardid,$openid);
        if($has_flag['errno'] > 0){
            //没购买会员卡
            return app_error(82034,$has_flag['msg']);
        }else{
            //会员卡已过期
            if($has_flag['using']=='-1'){
                return app_error(AppError::$CardisOverTime);
            }
        }

        if(!$card['is_month_coupon']){
            return app_error(82034,'此会员卡没有此项福利');
        }
        $month_coupon = iunserializer($card['month_coupon']);
        if(empty($month_coupon) || empty($month_coupon['couponid'])){
            return app_error(82035,'会员卡数据有误');
        }
        if(!in_array($couponid,$month_coupon['couponid'])){
            return app_error(82035,'会员卡数据有误(1)');
        }
        $couponnum = $month_coupon['couponnum'.$couponid];
        if(empty($couponnum) || $couponnum < 1){
            return app_error(82035,'会员卡数据有误(2)'.$couponnum);
        }
        //判断是否本月领取过
        if($plugin_membercard->check_month_coupon($cardid,$openid,$couponid)){
            return app_error(82033,'本月已领取过此优惠券');
        }

        //赠送优惠券
        $send_res = $plugin_membercard->send_coupon($openid,$couponid,$couponnum,1);
        if(!$send_res){
            return app_error(AppError::$SystemError);
        }

        $send_log = array(
            'uniacid'           => $uniacid,
            'openid'            => $openid,
            'member_card_id'   => $cardid,
            'name'              => $card['name'],
            'receive_time'     => TIMESTAMP,
            'create_time'      => TIMESTAMP,
            'price'             => $card['price'],
            'validate'          => $card['validate'],
            'sendtype'          => 2,
            'card_couponid'    => $couponid,
            'card_couponcount' => $couponnum
        );
        pdo_insert('ewei_shop_member_card_monthsend', $send_log);
        return app_json(array('data'=>$send_log));
    }
}