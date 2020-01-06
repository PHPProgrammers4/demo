<?php

/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */

if(!defined('IN_IA')) {

     exit('Access Denied');

}


return array(
    'version'=>'1.0',
    'id'=>'goodscircle',
    'name'=>'好物圈',
    'v3'=>true,
    'menu'=>array(
        'title'=>'页面',
        'plugincom'=>1,
        'icon'=>'page',
        'items'=>array(
            array(
                'title'=>'设置',
                'route'=>'index'
            ),
        )
    )
);

