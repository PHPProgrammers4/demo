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
require EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class Single_Refund_EweiShopV2Page extends AppMobilePage {

    protected function globalData() {
        global $_W, $_GPC;
        $order_goodsid = intval($_GPC['id']);
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];

        //订单商品
        $order = pdo_fetch('select o.id,o.price,o.couponprice,o.iscycelbuy,o.status,o.virtual,o.isverify,o.refundstate,o.finishtime,o.deductprice,o.deductcredit2,o.dispatchprice,o.deductenough,o.merchdeductenough,g.cannotrefund,og.single_refundid,og.single_refundstate,og.single_refundtime,og.realprice as og_realprice,o.grprice,og.consume,o.ispackage,og.sendtime from ' . tablename('ewei_shop_order') .' o '
            . ' left join ' . tablename('ewei_shop_order_goods') . ' og on og.orderid=o.id'
            . ' left join ' . tablename('ewei_shop_goods') . ' g on g.id=og.goodsid'
            . ' where og.id=:ogid and o.openid=:openid and o.uniacid=:uniacid'
            , array(':ogid' => $order_goodsid,':openid' => $openid,':uniacid' => $uniacid));

//        dump(iunserializer($order['consume']));die;

        if (empty($order)) {
            app_error(AppError::$OrderNotFound);
        }

        $_err = '';
        if($order['iscycelbuy'] == 1 ){
            //查询分期订单下面是否存在有开始的周期商品
            $order_goods = pdo_fetch( "select * from ".tablename( 'ewei_shop_cycelbuy_periods' )."where orderid = {$order['id']} and status != 0" );
            if( !empty($order_goods) ){
                $_err='订单已经开始，无法进行退款';
            }
        }

        if(!empty($order['ispackage'])){
            $_err = '套餐订单,无法进行单品维权!';
        }

        //代付
        $ispeerpay = m('order')->checkpeerpay($order['id']);//检查是否是代付订单
        if(!empty($ispeerpay)){
            $_err = '代付订单,无法进行单品维权!';
        }

        //存在全返，无法进行单商品退款
        $fullback_goods_count = pdo_fetchcolumn('select count(og.id) from ' . tablename('ewei_shop_order_goods') .' og '
            . ' left join ' . tablename('ewei_shop_goods') . ' g on g.id=og.goodsid'
            . ' where og.orderid=:orderid and g.isfullback>0 and og.uniacid=:uniacid'
            , array(':orderid' => $order['id'], ':uniacid' => $uniacid));
        if(!empty($fullback_goods_count)){
            $_err = '全返订单,无法进行单商品退款';
        }

        if ($order['status'] == 0) {
            $_err = '订单未付款，不能申请退款!';
        } elseif($order['status']==2 && !empty($order['cannotrefund'])) {
            $_err = '此商品不可退换货!';
        }elseif($order['status'] == 3) {
            if (!empty($order['virtual']) || $order['isverify'] == 1) {
                $_err = '此订单不允许退款!';
            } else {
                //申请退款
                $tradeset = m('common')->getSysset('trade');
                $refunddays = intval($tradeset['refunddays']);
                if ($refunddays > 0) {
                    $days = intval((time() - $order['finishtime']) / 3600 / 24);
                    if ($days > $refunddays) {
                        $_err = '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!';
                    }
                } else {
                    $_err = '订单完成, 无法申请退款!';
                }
            }
        }

        if (!empty($_err)) {
            app_error(AppError::$OrderCanNotRefund, $_err);
        }

        //订单所有商品 关联 退款表
        $order_goods = pdo_fetchall('select og.id,og.single_refundid,og.single_refundstate,r.applyprice from ' . tablename('ewei_shop_order_goods') .' og '
            . ' left join ' . tablename('ewei_shop_order_single_refund') . ' r on r.id=og.single_refundid'
            . ' where og.orderid=:orderid and og.uniacid=:uniacid'
            , array(':orderid' => $order['id'], ':uniacid' => $uniacid));

        $is_last=true;//是否是订单最后一个退款商品
        $refund_price=0;//订单商品退款总额
        foreach ($order_goods as $og){

            //过滤当前申请的ordergoods
            if(intval($og['id']) != $order_goodsid){
                if(empty($og['single_refundid'])){
                    $is_last=false;
                    break;
                }

                //其他商品退款总额
                $refund_price+=$og['applyprice'];
            }
        }

        if($is_last){
            //应退金额=订单实际付款金额-其他商品退款总额
            $order['single_refundprice']=$order['price']-$refund_price;
            if ($order['status'] > 2) {
                //如果已经发货，退款金额减去运费
                $order['single_refundprice'] -= $order['dispatchprice'];
            }
        }else{
            //订单优惠金额(不包含商品促销或会员折扣) = 积分抵扣金额+余额抵扣金额+商城满减金额+多商户满减金额+优惠券金额
            $order_discount=$order['deductprice']+$order['deductcredit2']+$order['deductenough']+$order['merchdeductenough']+$order['couponprice'];

            //商品优惠金额 = 订单优惠金额 x ( 商品实际付款金额(折扣后的商品价格,不包含运费) / 订单商品实际总价(折扣后的订单总价,不包含运费) )
            $goods_discount =round($order_discount*($order['og_realprice']/$order['grprice']),2);

            //应退金额=商品实际付款金额(折扣后的商品价格,不包含运费)-商品优惠金额
            $order['single_refundprice']=$order['og_realprice']-$goods_discount;
        }

        if($order['single_refundprice']<=0){
            $order['single_refundprice']=0;
        }

        return array(
            'uniacid' => $uniacid,
            'openid' => $_W['openid'],
            'order_goodsid' => $order_goodsid,
            'order' => $order,
            'single_refundid' => $order['single_refundid'],

        );
    }

    function main() {

        global $_W, $_GPC;
        extract($this->globalData());
        if($order['status'] == 2 && $order['price'] == $order['dispatchprice']) {
            $canreturns = 1;
        }
//        if ( $order['status'] == '-1'){
//            $this->message('请不要重复提交!','','error');
//        }
        $refund = false;

        if (!empty($single_refundid)) {
            $refund = pdo_fetch("select * from " . tablename('ewei_shop_order_single_refund') . ' where id=:id and ordergoodsid=:ordergoodsid and uniacid=:uniacid limit 1'
                , array(':id' => $single_refundid,':ordergoodsid' => $order_goodsid,':uniacid' => $uniacid));
            if (!empty($refund['refundaddress'])) {
                $refund['refundaddress'] = iunserializer($refund['refundaddress']);
            }
        }
        if (!empty($refund['imgs'])) {
            $img_list=array();
            $imgs=iunserializer($refund['imgs']);
            foreach ($imgs as $img){
                $img_list[]=tomedia($img);
            }
            $refund['imgs']=$img_list;
        }



        if (empty($refund)) {
            $show_price =round( $order['single_refundprice'],2);
        } else {
            $show_price = round($refund['applyprice'],2);
            $refund['createtime'] = date('Y-m-d H:i:s',$refund['createtime']);

            $reasonArr = array('不想要了','卖家缺货','拍错了/订单信息错误','其它');
            $reasonIndex = array_search($refund['reason'],$reasonArr);
            if ($refund['status']==3){
                $refund['statusstr'] = '需填写快递单号';
            }elseif($refund['status']==4){
                $refund['statusstr'] = '等待商家确认';
            }elseif($refund['status']==5){
                $refund['statusstr'] = '商家已经发货';
            }
        }

        $express_list = m('express')->getExpressList();

        app_json(array(
            'title' => ($order['status']==1?'退款':'售后').'申请',
            'refundtype' => $refund['rtype'],
            'refundreason' => ($refund['rtype']=='2'?'换货':'退款').'原因',
            'refundexplain' => ($refund['rtype']=='2'?'换货':'退款').'说明',
            'price' => $show_price,
            'content' => isset($refund['content'])?$refund['content']:'',
            'order'=>$order,
            'refund'=>$refund,
            'images'=>is_array($refund['imgs'])?$refund['imgs']:array(),
            'imgs'=>!empty($images)?$images:array(),
            'express_list'=>$express_list,
            'rtypeIndex'=> !empty($refund)?$refund['rtype']:0,
            'reasonIndex'=> !empty($reasonIndex)?$reasonIndex:0
        ));
    }

    //提交
    function submit() {

        global $_W, $_GPC;
        extract($this->globalData());
        if ($order['single_refundstate'] == '9'){
            app_error(AppError::$OrderCanNotRefund, '商品维权已经处理完毕');
        }
        $price = floatval($_GPC['price']);
        $rtype = intval($_GPC['rtype']);
        if ($rtype != 2) {
            if (empty($price) && $order['deductprice'] == 0) {
                app_error(AppError::$OrderCanNotRefund, '退款金额不能为0元');
            }

            if (bccomp($price,$order['single_refundprice'],2)==1 ) {
                app_error(AppError::$OrderCanNotRefund,'退款金额不能超过' . $order['single_refundprice'] . '元');
            }
        }

        $refund = array(
            'uniacid' => $uniacid,
            'merchid' => $order['merchid'],
            'applyprice' => $price,
            'rtype' => $rtype,
            'reason' => trim($_GPC['reason']),
            'content' => trim($_GPC['content']),
            'imgs' => iserializer($_GPC['images'])
        );

        if ($refund['rtype'] == 2) {
            $refundstate = 2;
        } else {
            $refundstate = 1;
        }
        if ($order['single_refundstate'] == 0) {
            //新建一条退款申请
            $refund['createtime'] = time();
            $refund['orderid'] = $order['id'];
            $refund['ordergoodsid'] = $order_goodsid;
            $refund['ordergoodsrealprice'] = $order['og_realprice'];//商品实际付款金额(折扣后的商品价格,不包含运费)
            $refund['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');
            pdo_insert('ewei_shop_order_single_refund', $refund);
            $single_refundid = pdo_insertid();
            pdo_update('ewei_shop_order_goods', array('single_refundid' => $single_refundid, 'single_refundstate' => $refundstate), array('id' => $order_goodsid, 'uniacid' => $uniacid));
        } else {
            //修改退款申请
            $refund['status']=0;
            pdo_update('ewei_shop_order_goods', array('single_refundstate' => $refundstate), array('id' => $order_goodsid, 'uniacid' => $uniacid));
            pdo_update('ewei_shop_order_single_refund', $refund, array('id' => $single_refundid, 'uniacid' => $uniacid));
        }

        pdo_update('ewei_shop_order', array('refundstate' => 3,'refundtime'=>0), array('id' => $order['id'], 'uniacid' => $uniacid));

        //模板消息
        m('notice')->sendOrderMessage($order['id'], true);
        app_json();
    }

    //取消
    function cancel() {

        global $_W, $_GPC;
        extract($this->globalData());
        $change_refund = array();
        $change_refund['status'] = -2;
        $change_refund['refundtime'] = time();
        pdo_update('ewei_shop_order_single_refund', $change_refund, array('id' => $single_refundid, 'uniacid' => $uniacid));
        pdo_update('ewei_shop_order_goods', array('single_refundstate' => 0), array('id' => $order_goodsid, 'uniacid' => $uniacid));

        $order_goods=pdo_fetchall("select single_refundid,single_refundstate,single_refundtime from ".tablename('ewei_shop_order_goods')." where orderid=:orderid",array(':orderid'=>$order['id']));
        $refund_num=0;//退款过的订单商品数量
        $apply_refund_num=0;//申请维权中的订单商品数量
        foreach ($order_goods as $og){
            if($og['single_refundtime']>0){
                $refund_num++;
            }
            if($og['single_refundstate']==1 || $og['single_refundstate']==2){
                $apply_refund_num++;
            }
        }

        if(empty($apply_refund_num) && !empty($refund_num)){
            pdo_update('ewei_shop_order', array('refundtime'=>time()), array('id' => $order['id'], 'uniacid' => $uniacid));
        }

        if(empty($apply_refund_num) && empty($refund_num)){
            pdo_update('ewei_shop_order', array('refundstate' => 0,'refundtime'=>0), array('id' => $order['id'], 'uniacid' => $uniacid));
        }

        app_json();
    }

    //填写快递单号
    function express() {

        global $_W, $_GPC;
        extract($this->globalData());
        if (empty($single_refundid)) {
            app_error(AppError::$ParamsError, '参数错误');
        }
        if (empty($_GPC['expresssn'])) {
            app_error(AppError::$ParamsError, '请填写快递单号');
        }
        $refund = array(
            'status'=>4,
            'express'=>trim($_GPC['express']),
            'expresscom'=>trim($_GPC['expresscom']),
            'expresssn'=>trim($_GPC['expresssn']),
            'sendtime'=>time()
        );
        pdo_update('ewei_shop_order_single_refund', $refund, array('id' => $single_refundid, 'uniacid' => $uniacid));
        app_json();
    }

    //收到换货商品
    function receive(){

        global $_W, $_GPC;
        extract($this->globalData());
        $single_refundid = intval($_GPC['single_refundid']);
        $refund =  pdo_fetch("select * from " . tablename('ewei_shop_order_single_refund') . ' where id=:id and ordergoodsid=:ordergoodsid and uniacid=:uniacid limit 1'
            , array(':id' => $single_refundid,':ordergoodsid' => $order_goodsid,':uniacid' => $uniacid,));
        if (empty($refund)) {
            app_error(AppError::$OrderNotFound, '换货申请未找到');
        }

        $time = time();
        $refund_data = array();
        $refund_data['status'] = 1;
        $refund_data['refundtime'] = $time;
        pdo_update('ewei_shop_order_single_refund', $refund_data, array('id'=>$single_refundid, 'uniacid' => $uniacid));

        $order_data = array();
        $order_data['single_refundstate'] = 9;
        pdo_update('ewei_shop_order_goods', $order_data, array('id'=>$order_goodsid, 'uniacid' => $uniacid));

        //查询其它 订单商品 是否有正在维权中的
        $is_single_refund=pdo_fetchcolumn('select count(id) from '.tablename('ewei_shop_order_goods').'where orderid=:orderid and (single_refundstate=1 or single_refundstate=2)',array(':orderid'=>$order['id']));

        //如果其它 订单商品 没有正在维权中
        if(empty($is_single_refund)){
            //更新订单维权时间
            pdo_update('ewei_shop_order', array('refundtime' => $time), array('id' => $order['id'], 'uniacid' => $uniacid));
        }
        app_json();

    }

    //查询商家重新发货快递
    function refundexpress() {

        global $_W, $_GPC;
        extract($this->globalData());

        $express = trim($_GPC['express']);
        $expresssn = trim($_GPC['expresssn']);
        $expresscom = trim($_GPC['expresscom']);
        $expresslist = m('util')->getExpressList($express, $expresssn);

        app_json(array('list'=>$expresslist));
    }
}
