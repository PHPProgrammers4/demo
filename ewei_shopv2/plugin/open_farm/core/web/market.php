<?php
//dezend by http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Market_EweiShopV2Page extends PluginWebPage
{
	/**
	private $table = 'ewei_open_farm_market';
	/**
	private $field = array('id', 'uniacid', 'title', 'type', 'value', 'logo', 'egg', 'number', 'create_time');
	/**
	private $message = array('title' => '请填写商品名字', 'type' => '请选择兑换种类', 'value' => '请填写积分数量', 'logo' => '请填写集市商品logo', 'egg' => '请填写所需鸡蛋数量', 'number' => '请填写兑换数量');

	/**
	public function __construct($_init = true)
	{
		parent::__construct($_init);
	}

	/**
	public function main()
	{
		require_once $this->template();
	}

	/**
	public function addInfo()
	{
		global $_W;
		global $_GPC;
		$data = $_GPC['__input'];
		$where = array('id' => $data['id']);
		$data['uniacid'] = $_W['uniacid'];
		$this->checkInfo($data);
		$data = $this->model->removeUselessField($data, $this->field);

		if ($data['id']) {
			$noticeAdd = pdo_update($this->table, $data, $where);
		}
		else {
			$noticeAdd = pdo_insert($this->table, $data);
		}

		$this->model->returnJson($noticeAdd);
	}

	/**
	public function getInfo()
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['__input']['id'];
		$configInfo = pdo_get($this->table, array('id' => $id));
		$configInfo['show_logo'] = tomedia($configInfo['logo']);
		$this->model->returnJson($configInfo);
	}

	/**
	public function getType()
	{
		$table = 'ims_ewei_open_farm_market';
		$field = 'type';
		$typeArr = $this->model->getEnumList($table, $field);
		$this->model->returnJson($typeArr);
	}

	/**
	public function getList()
	{
		global $_W;
		global $_GPC;
		$condition = array('uniacid' => $_W['uniacid']);
		$search = $_GPC['__input']['search'];
		$currentPage = intval($_GPC['page']);
		$pageSize = 10;
		$context = array('before' => 5, 'after' => 4, 'ajaxcallback' => true, 'callbackfuncname' => 'function.get_list');

		try {
			$sql = 'SELECT * FROM ' . tablename($this->table) . 'WHERE `uniacid`=' . $_W['uniacid'];

			if ($search) {
				$sql .= ' AND `title` LIKE \'%' . $search . '%\' ';
				$sqlCount = 'SELECT COUNT(*) AS `count` FROM ' . tablename($this->table) . (' WHERE `uniacid`=' . $_W['uniacid'] . ' AND( `title` LIKE "%' . $search . '%") ');
				$totalArr = pdo_fetchall($sqlCount);
				$total = $totalArr[0]['count'];
			}
			else {
				$total = pdo_count($this->table, $condition);
			}

			$sql .= ' ORDER BY id DESC ';
			$sql .= ' LIMIT ' . ($currentPage - 1) * $pageSize . ',' . $pageSize;
			$marketList = pdo_fetchall($sql, $condition);
			$marketList = $this->model->forTomedia($marketList, 'logo', 'show_logo');
			$pages = pagination($total, $currentPage, $pageSize, '', $context);
			$this->model->returnJson($marketList, $pages);
		}
		catch (Exception $e) {
			$this->model->errorMessage($_W['isajax'], $e->getMessage());
		}
	}

	/**
	public function deleteInfo()
	{
		global $_W;
		global $_GPC;
		$id = $_GPC['__input']['id'];
		$query = pdo_delete($this->table, array('id' => $id));
		$this->model->returnJson($query);
	}

	/**
	private function checkInfo($data)
	{
		$this->model->checkDataRequired($data, $this->message);
	}
}

?>