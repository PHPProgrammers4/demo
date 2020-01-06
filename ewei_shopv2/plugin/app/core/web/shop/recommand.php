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

class Recommand_EweiShopV2Page extends WebPage {

	function main() {

		global $_W, $_GPC;

		
		if ($_W['ispost']) {
			$shop = $_W['shopset']['shop'];
			$shop['indexrecommands_wxapp'] = $_GPC['goodsid'];
			m('common')->updateSysset(array('shop' => $shop));
			plog('app.shop.recommand', '修改首页推荐商品设置');
			show_json(1);
		}
		
		$goodsids = isset($_W['shopset']['shop']['indexrecommands_wxapp'])?implode(",",$_W['shopset']['shop']['indexrecommands_wxapp']):'';
		$goods = false;
		if(!empty($goodsids)){
			$goods = pdo_fetchall('select id,title,thumb from '.tablename('ewei_shop_goods')." where id in ({$goodsids}) and status=1 and deleted=0 and uniacid={$_W['uniacid']} order by instr('{$goodsids}',id)");
		}
		$goodsstyle = $_W['shopset']['shop']['goodsstyle_wxapp'];
					
		include $this->template();
	}
	
	function setstyle(){
		global $_W, $_GPC;
		$shop = $_W['shopset']['shop'];
		$shop['goodsstyle_wxapp'] = intval($_GPC['goodsstyle']);
		m('common')->updateSysset(array('shop' => $shop));
		plog('app.shop.recommand', '修改手机端商品组样式');
		show_json(1);
	}

}
