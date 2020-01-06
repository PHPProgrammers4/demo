<?php
//dezend by http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Grade_EweiShopV2Page extends PluginMobilePage
{
	/**
	private $table = 'ewei_open_farm_grade';

	/**
	public function main()
	{
		require_once $this->template();
	}

	/**
	public function getInfo($chicken, $method = false)
	{
		global $_W;
		$where = array('uniacid' => $_W['uniacid'], 'level' => $chicken['level']);
		$gradeInfo = pdo_get($this->table, $where);

		if ($method) {
			return $gradeInfo;
		}

		$this->model->returnJson($gradeInfo);
	}

	/**
	public function checkLevel($data)
	{
		global $_W;
		$where = array('uniacid' => $_W['uniacid'], 'level' => $data['level']);
		$currentInfo = pdo_get($this->table, $where);

		if ($currentInfo['experience'] <= $data['experience']) {
			$where = array('uniacid' => $_W['uniacid'], 'level' => $data['level'] + 1);
			$info = pdo_get($this->table, $where);

			if ($info) {
				$data['experience'] = $data['experience'] - $currentInfo['experience'];
				$data['level'] = $info['level'];
				$data['accelerate'] = $info['accelerate'];
				$data['surprised_guard'] = $info['surprised_guard'];

				if ($info['experience'] <= $data['experience']) {
					$data = $this->checkLevel($data);
				}
			}
			else {
				$tableName = tablename($this->table);
				$sql = ' SELECT * FROM ' . $tableName . ' ' . (' WHERE `uniacid` = ' . $_W['uniacid'] . ' ') . ' ORDER BY `level` DESC ' . ' LIMIT 1 ';
				$info = pdo_fetch($sql);
				$data['level'] = $info['level'];
				$data['accelerate'] = $info['accelerate'];
				$data['surprised_guard'] = $info['surprised_guard'];
			}
		}

		return $data;
	}
}

?>