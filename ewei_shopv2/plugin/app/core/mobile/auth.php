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

class Auth_EweiShopV2Page extends AppMobilePage
{
    function __construct()
    {
        global $_W;
        $this->authkey = $_W['setting']['site']['token'].'_'.$_W['uniacid'] ;
    }

    function main() {
        global $_W, $_GPC;

        $token = trim($_GPC['token']);
        $callback = trim($_GPC['callback']);
        $callback = urldecode($callback);

        if(!empty($token) && !empty($callback)){
            $token = authcode(base64_decode($token), 'DECODE', $this->authkey);
            $params = explode("|", $token);
            if(!empty($params[0])){
                $member = m('member')->getMember($params[0]);
                if(!empty($member)){    // 跳转
                    if(strexists($callback, '&c=entry&m=ewei_shopv2&do=mobile')){
                        m('account')->setLogin($member);
                    }
                }
            }
        }

        header('location: ' . $callback);
    }

    //获取apptoken
    function token(){

        global $_GPC;
        $token = trim($_GPC['token']);
        if(!empty($token)) {

            //如果传入token,则检测token
            $token = authcode(base64_decode($token), 'DECODE', '*736bg%21@');
            if (!empty($token)) {
                return app_json(array('token' => $token));
            } else {
                return app_error(AppError::$UserTokenFail);
            }
        }
        return app_json();
    }
    
}