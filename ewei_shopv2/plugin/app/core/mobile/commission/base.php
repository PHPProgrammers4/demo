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

class Base_EweiShopV2Page extends AppMobilePage
{
    public function __construct() {
        parent::__construct();
        global $_W, $_GPC;
        if ($_W['action'] != 'commission.register' && $_W['action'] != 'myshop' && $_W['action'] != 'share') {
            $member = $this->member;
            if ($member['isagent'] != 1 || $member['status'] != 1) {
                die(app_error(AppError::$CommissionReg, $_W['openid'].'+'.$member['openid']));
            }
        }
        $this->model = p('commission');
        $this->set = $this->model->getSet();
    }
}