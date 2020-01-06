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

class Cube_EweiShopV2Page extends WebPage {

	function main() {
		global $_W, $_GPC;

		if ($_W['ispost']) {
			//处理幻灯片
			$imgs = $_GPC['cube_img'];
			$urls = $_GPC['cube_url'];
			$cubes = array();
			if (is_array($imgs)) {
				foreach ($imgs as $key => $img) {
					$cubes[] = array(
						'img' => save_media($img),
						'url' => trim($urls[$key])
					);
				}
			}
			$shop = $_W['shopset']['shop'];
			$shop['cubes_wxapp'] = $cubes;
			m('common')->updateSysset(array('shop' => $shop));
			plog('app.shop.cube.edit', '修改基本设置');
			show_json(1);
		}
        $cubes = isset($_W['shopset']['shop']['cubes_wxapp']) ? $_W['shopset']['shop']['cubes_wxapp'] : array();
		include $this->template();
	}

}
