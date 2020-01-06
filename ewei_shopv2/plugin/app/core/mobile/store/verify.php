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

class Verify_EweiShopV2Page extends AppMobilePage {

    function log() {

        global $_W, $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;

        $condition = ' vg.uniacid = :uniacid';
        $params = array(':uniacid' => $_W['uniacid']);
//        $params = array(':uniacid' => 4);

//        $searchfield = strtolower(trim($_GPC['searchfield']));
        $keyword = trim($_GPC['keyword']);
//        if (!empty($searchfield) && !empty($keyword)) {
//            if ($searchfield == 'ordersn') {
//                $condition.=' and o.ordersn like :keyword';
//            } else if ($searchfield == 'verifyid') {
//                $condition.=' and vg.id like :keyword';
//            }else if ($searchfield == 'store') {
//                $condition.=' and s.storename like :keyword';
//            }else if ($searchfield == 'goodtitle') {
//                $condition.=' and g.title like :keyword';
//            }



        $condition.=' and g.title like :keyword';

            $params[':keyword'] = "%{$keyword}%";
//        }

        if (!empty($_GPC['verifydate']['start']) && !empty($_GPC['verifydate']['end'])) {
            $verifystarttime = strtotime($_GPC['verifydate']['start']);
            $verifyendtime = strtotime($_GPC['verifydate']['end']);

            $condition .= " AND vgl.verifydate >= :verifystarttime AND vgl.verifydate <= :verifyendtime ";
            $params[':verifystarttime'] = $verifystarttime;
            $params[':verifyendtime'] = $verifyendtime;
        }

        if (!empty($_GPC['buydate']['start']) && !empty($_GPC['buydate']['end'])) {
            $buystarttime = strtotime($_GPC['buydate']['start']);
            $buyendtime = strtotime($_GPC['buydate']['end']);

            $condition .= " AND o.paytime >= :buystarttime AND o.paytime <= :buyendtime ";
            $params[':buystarttime'] = $buystarttime;
            $params[':buyendtime'] = $buyendtime;
        }

        $sql = 'select vgl.id as logid,vg.*,g.id as goodsid ,g.title,g.thumb,o.ordersn,vgl.verifydate,vgl.verifynum,o.paytime,s.storename,sa.salername,vgl.remarks,o.openid  from ' . tablename('ewei_shop_verifygoods_log') . '   vgl
		 left join ' . tablename('ewei_shop_verifygoods') . ' vg on vg.id = vgl.verifygoodsid
		 left join ' . tablename('ewei_shop_store') . ' s  on s.id = vgl.storeid
		 left join ' . tablename('ewei_shop_saler') . ' sa  on sa.id = vgl.salerid
		 left join ' . tablename('ewei_shop_order_goods') . ' og on vg.ordergoodsid = og.id
		 left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid
		 left join ' . tablename('ewei_shop_goods') . ' g on og.goodsid = g.id
		 where  1 and  '.$condition.' ORDER BY vgl.verifydate DESC ';

        if (empty($_GPC['export'])) {
            $sql.=' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
        }



//        print_r($sql);die();


        $list = pdo_fetchall($sql, $params);

        $list = set_medias($list, "thumb");

        foreach($list as &$rom)
        {
            $member = m("member")->getMember($rom['openid']);

            $rom['username']=$member['realname'];
            $rom['mobile']=$member['mobile'];
        }

        unset($rom);


        $total = pdo_fetchcolumn('select  COUNT(*)   from ' . tablename('ewei_shop_verifygoods_log') . '   vgl
		 left join ' . tablename('ewei_shop_verifygoods') . ' vg on vg.id = vgl.verifygoodsid
		 left join ' . tablename('ewei_shop_store') . ' s  on s.id = vgl.storeid
		 left join ' . tablename('ewei_shop_saler') . ' sa  on sa.id = vgl.salerid
		 left join ' . tablename('ewei_shop_order_goods') . ' og on vg.ordergoodsid = og.id
		 left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid
		 left join ' . tablename('ewei_shop_goods') . ' g on og.goodsid = g.id
		  where  1 and  '.$condition.' ORDER BY vgl.verifydate DESC ', $params);
        $pager = pagination2($total, $pindex, $psize);


        return app_json( array('list'=>$list));
    }

    function  get_detail(){
        global $_W, $_GPC;


        $condition = ' vg.uniacid = :uniacid and vgl.id=:logid';
        $params = array(':uniacid' => $_W['uniacid'],'logid'=>$_GPC['logid']);
//        $params = array(':uniacid' => 4,'logid'=>$_GPC['logid']);

        $sql = 'select vg.*,g.id as goodsid ,g.title,g.thumb,o.ordersn,vgl.verifydate,vgl.verifynum,o.paytime,s.storename,sa.salername,vgl.remarks,o.openid  from ' . tablename('ewei_shop_verifygoods_log') . '   vgl
		 left join ' . tablename('ewei_shop_verifygoods') . ' vg on vg.id = vgl.verifygoodsid
		 left join ' . tablename('ewei_shop_store') . ' s  on s.id = vgl.storeid
		 left join ' . tablename('ewei_shop_saler') . ' sa  on sa.id = vgl.salerid
		 left join ' . tablename('ewei_shop_order_goods') . ' og on vg.ordergoodsid = og.id
		 left join ' . tablename('ewei_shop_order') . ' o on o.id = og.orderid
		 left join ' . tablename('ewei_shop_goods') . ' g on og.goodsid = g.id
		 where  1 and  '.$condition.' ORDER BY vgl.verifydate DESC ';

        $list = pdo_fetchall($sql, $params);

        $list = set_medias($list, "thumb");


        foreach($list as &$rom)
        {
            $member = m("member")->getMember($rom['openid']);

            $rom['username']=$member['realname'];
            $rom['mobile']=$member['mobile'];

            $rom['paytime']= date('Y-m-d H:i:s',$rom['paytime']);
            $rom['verifydate']= date('Y-m-d H:i:s',$rom['verifydate']);
        }

        unset($rom);

        return app_json( array('list'=>$list));
    }






}
