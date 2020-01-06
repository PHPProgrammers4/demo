<?php
//dezend by http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Surprised_EweiShopV2Page extends PluginMobilePage
{
	/**
	private $table = 'ewei_open_farm_surprised';
	/**
	private $field = array('id', 'uniacid', 'value', 'category', 'probability', 'create_time');

	/**
	public function deleteInfo($id)
	{
		global $_W;
		global $_GPC;
		$query = pdo_delete($this->table, array('id' => $id));
		return $query;
	}

	/**
	public function deleteUserSurprised()
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['__input']['id'];
		$table = 'ewei_open_farm_user_surprised';
		$query = pdo_delete($table, array('id' => $id));
		$this->model->returnJson($query);
	}

	/**
	public function deleteInfoByCoupon($id)
	{
		global $_W;
		global $_GPC;
		$where = array('uniacid' => $_W['uniacid'], 'category' => '优惠券', 'value' => $id);
		$infoArr = pdo_getall($this->table, $where);
		pdo_delete($this->table, $where);
		$infoIdArr = array();

		foreach ($infoArr as $key => $value) {
			$infoIdArr[] = $value['id'];
		}

		$infoIdStr = implode(',', $infoIdArr);
		$table = 'ewei_open_farm_user_surprised';
		$tableName = tablename($table);
		$sql = ' DELETE FROM ' . $tableName . ' ' . (' WHERE `surprised_id` IN (' . $infoIdStr . ') ') . (' AND `uniacid` = ' . $_W['uniacid'] . ' ');
		pdo_query($sql);
	}

	/**
	public function clearCouponSurprised()
	{
		global $_W;
		$where = array('uniacid' => $_W['uniacid'], 'category' => '优惠券');
		$surprisedArr = pdo_getall($this->table, $where);
		$couponIdArr = array();

		foreach ($surprisedArr as $key => $value) {
			$couponIdArr[] = $value['value'];
		}

		$couponIdStr = implode(',', $couponIdArr);
		$table = 'ewei_shop_coupon';
		$tableName = tablename($table);
		$sql = ' SELECT * FROM ' . $tableName . ' ' . (' WHERE `uniacid` = ' . $_W['uniacid'] . ' ') . (' AND `id` IN (' . $couponIdStr . ') ');
		$couponArr = pdo_fetchall($sql);

		foreach ($couponArr as $key => $value) {
			if ($value['total'] === 0) {
				$this->deleteInfoByCoupon($value['id']);
			}
		}
	}
}

?>