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

class Cityexpress_EweiShopV2Page extends AppMobilePage {

    function  map(){
        global $_W, $_GPC;

        $cityexpress = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_city_express') . " WHERE uniacid=:uniacid AND merchid=:merchid",array(":uniacid"=>$_W['uniacid'],":merchid"=>0));
        $address = m('common')->getSysset('contact');//获取商城地址
        $shop = m('common')->getSysset('shop');//获取商城名称和log


        if(!empty($address)){
            $cityexpress['address']=$address['province'].$address['city'].$address['address'];
        }

        if(!empty($shop)){
            $cityexpress['name']=$shop['name'];
            $cityexpress['logo']=tomedia($shop['logo']);
        }


        return app_json(array('cityexpress' => $cityexpress));
    }



}
