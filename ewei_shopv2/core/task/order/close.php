<?php
/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */

error_reporting(0);
require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/ewei_shopv2/defines.php';
require '../../../../../addons/ewei_shopv2/core/inc/functions.php';
global $_W;

ignore_user_abort(); //忽略关闭浏览器
set_time_limit(0); //永远执行

m('cache') -> get('ewei_shop_close_order_limit_start');
$limit_page = 60;
$count = pdo_fetch('select count(*) as count from ' . tablename('ewei_shop_sysset'));
$limit_count = ceil($count['count']/$limit_page);
if(m('cache') -> get('ewei_shop_close_order_limit_start') <= $limit_count-1){
    $sets = pdo_fetchall('select uniacid from ' . tablename('ewei_shop_sysset').' limit '.m('cache') -> get('ewei_shop_close_order_limit_start')*$limit_page .','.$limit_page);
    m('cache') -> set('ewei_shop_close_order_limit_start',m('cache') -> get('ewei_shop_close_order_limit_start')+1);
}else{
    m('cache') -> set('ewei_shop_close_order_limit_start',0);
    $sets = pdo_fetchall('select uniacid from ' . tablename('ewei_shop_sysset').' limit '.m('cache') -> get('ewei_shop_close_order_limit_start')*$limit_page .','.$limit_page);
    m('cache') -> set('ewei_shop_close_order_limit_start',1);
}

foreach ($sets as $set) {
    $_W['uniacid'] = $set['uniacid'];


    if (empty($_W['uniacid'])) {
        continue;
    }

    $trade = m('common')->getSysset('trade', $_W['uniacid']);
    if (isset($trade['closeorder_virtual']) && !empty($trade['closeorder_virtual'])) {
        $min = intval($trade['closeorder_virtual']);
    }else{
        $min = 15;
    }

    if ($min > 0) {
        $mintimes = 60 * $min;
        $orders = pdo_fetchall("select id,openid,deductcredit2,ordersn,isparent,deductcredit,deductprice,status,isparent,isverify,`virtual`,`virtual_info`,createtime,merchid from " . tablename('ewei_shop_order') . " where uniacid={$_W['uniacid']}  and paytype<>3  and `virtual` <> 0 and createtime + {$mintimes} <=unix_timestamp() and status=0");
        $p = com('coupon');
        if (count($orders)  != 0) {
            foreach ($orders as $o) {
                //判断是否存在卡密数据
                if (!empty($o['virtual']) && $o['virtual'] != 0) {

                    if($o['status']==0){
                        //检查是否是代付订单
                        $isPeerpay = m('order')->checkpeerpay($o['id']);
                        //如果是代付则跳过
                        if (!empty($isPeerpay)){
                            continue;
                        }
                        //如果是多商户父订单则跳过返还优惠和积分
                        if ($o['isparent'] == 0) {
                            if ($p) {
                                //退还优惠券
                                if (!empty($o['couponid'])) {
                                    $p->returnConsumeCoupon($o['id']); //自动关闭订单
                                }
                            }

//                            //处理订单库存及用户积分情况
//                            m('order')->setStocksAndCredits($o['id'], 2);
                            //返还抵扣余额
                            m('order')->setDeductCredit2($o);

//                            返还抵扣积分
                            if ($o['deductprice'] > 0) {
                                m('member')->setCredit($o['openid'], 'credit1', $o['deductcredit'], array('0', $_W['shopset']['shop']['name'] . "自动关闭订单返还抵扣积分 积分: {$o['deductcredit']} 抵扣金额: {$o['deductprice']} 订单号: {$o['ordersn']}"));
                            }
                        }
                        pdo_query("update " . tablename('ewei_shop_order') . ' set status=-1,canceltime=' . time() . ' where id=' . $o['id']);
                        m('finance')->closeOrder($o['ordersn']);
                        $goodsid = pdo_fetch('SELECT goodsid FROM '.tablename('ewei_shop_order_goods').' WHERE uniacid = '.$_W['uniacid'].' AND orderid = '.$o['id']);

                        $typeid = $o['virtual'];
                        $vkdata = ltrim($o['virtual_info'],'[');
                        $vkdata = rtrim($vkdata,']');
                        $arr = explode('}',$vkdata);
                        foreach($arr as $k => $v){
                            if(!$v){
                                unset($arr[$k]);
                            }
                        }
                        $vkeynum = count($arr);

                        //未付款卡密变为未使用
                        pdo_query("update " . tablename('ewei_shop_virtual_data') . ' set openid="",usetime=0,orderid=0,ordersn="",price=0,merchid='.$o['merchid'].' where typeid=' . intval($typeid).' and orderid = '.$o["id"]);

                        //模板减少使用数据
                        pdo_query("update " . tablename('ewei_shop_virtual_type') . " set usedata=usedata-".$vkeynum." where id=" .intval($typeid));

                        //还原商品库存
                        pdo_query("update " . tablename('ewei_shop_goods') . " set total=total+".$vkeynum." where id=" .intval($goodsid['goodsid']).' and uniacid = '.$_W['uniacid']);

                    }
                }
            }
        }

    }

    $days = intval($trade['closeorder']);
    if ($days <= 0) {
        //不自动关闭订单
        continue;
    }

    if($days > 0){
        $daytimes = 86400 * $days;
        $orders = pdo_fetchall("select id,openid,deductcredit2,ordersn,isparent,deductcredit,deductprice,status,isparent,isverify,couponid from " . tablename('ewei_shop_order') . " where uniacid={$_W['uniacid']}  and paytype<>3  and ((createtime + {$daytimes} <=unix_timestamp() and status=0) or (status = 1 and `isverify` = 1 and `verifyendtime` <= unix_timestamp() and `verifyendtime` > 0))");

        $p = com('coupon');
        foreach ($orders as $o) {
            if($o['status']==0){
                //检查是否是代付订单
                $isPeerpay = m('order')->checkpeerpay($o['id']);
                //如果是代付要退款
                if (!empty($isPeerpay)){
                    if (($isPeerpay['createtime']+86400*15)>time()){//代付订单15天关闭
                        continue;
                    }
                    $refundsql = "SELECT * FROM ".tablename("ewei_shop_order_peerpay_payinfo")." WHERE pid = :pid";
                    $refundlist = pdo_fetchall($refundsql, array(':pid'=>$isPeerpay['id']));
                    foreach ($refundlist as $k => $v){
                        $openid = $v['openid'];
                        if (!empty($openid) && !empty($v['tid'])) {//微信退款
                            $result = m('finance')->pay($openid, 1, $v['price'] * 100, $o['ordersn'], "退款: ".$v['price']."元 订单号: " . $o['ordersn']);
                            if (is_error($result)){//微信退款失败
                                m('member')->setCredit($openid, 'credit2', $v['price'], array(0, "退款: {$v['price']}元 订单号: " . $o['ordersn']));
                            }
                        }else{//余额退款
                            m('member')->setCredit($openid, 'credit2', $v['price'], array(0, "退款: {$v['price']}元 订单号: " . $o['ordersn']));
                        }
                    }
                }
                //如果是多商户父订单则跳过返还优惠和积分
                if ($o['isparent'] == 0) {
                    if ($p) {
                        //退还优惠券
                        if (!empty($o['couponid'])) {
                            $p->returnConsumeCoupon($o['id']); //自动关闭订单
                        }
                    }

                    //处理订单库存及用户积分情况
                    m('order')->setStocksAndCredits($o['id'], 2);

                    //返还抵扣余额
                    m('order')->setDeductCredit2($o);

                    //返还抵扣积分
                    if ($o['deductprice'] > 0) {
                        m('member')->setCredit($o['openid'], 'credit1', $o['deductcredit'], array('0', $_W['shopset']['shop']['name'] . "自动关闭订单返还抵扣积分 积分: {$o['deductcredit']} 抵扣金额: {$o['deductprice']} 订单号: {$o['ordersn']}"));
                    }
                }
                pdo_query("update " . tablename('ewei_shop_order') . ' set status=-1,canceltime=' . time() . ' where id=' . $o['id']);
                m('finance')->closeOrder($o['ordersn']);
            }elseif ($o['status'] == 1 && $o['isverify'] == 1){
                //如果是核销码过期的订单则关闭
                pdo_query("update " . tablename('ewei_shop_order') . ' set status=-1,canceltime=' . time() . ' where id=' . $o['id']);
                m('finance')->closeOrder($o['ordersn']);
            }
        }
    }


}


