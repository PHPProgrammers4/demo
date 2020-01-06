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

class ComWebPage extends WebPage
{


    public function __construct($_com = '')
    {
        parent::__construct();

        if (com('perm') && !com('perm')->check_com($_com)) {
            $this->message("你没有相应的权限查看");
        }

    }
}