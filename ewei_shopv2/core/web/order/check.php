<?php
if (!(defined('IN_IA'))) {

	exit('Access Denied');
}



class Check_EweiShopV2Page extends WebPage
{
	protected function queryData($status, $st)
	{
		global $_W;
		global $_GPC;


		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		if ($st == 'main') {
			$st = '';
		} else {
			$st = '.' . $st;
		}

		$where = '';
		$where .= " WHERE uniacid = :uniacid";
		$param[':uniacid'] = $_W['uniacid'];

		$status = $status?$status:0;
		$where .= " AND status = :status";
		$param[':status'] = $status;

		if (!(empty($_GPC['searchfield'])) && !(empty($_GPC['keyword']))) {
			$searchfield = trim(strtolower($_GPC['searchfield']));
			$_GPC['keyword'] = trim($_GPC['keyword']);

			if ($searchfield == 'out_trade_no') {
				$where .= " AND out_trade_no = :out_trade_no";
				$param[':out_trade_no'] = $_GPC['keyword'];
			}
		}

		$orderby = " order by id desc ";
	
		$sql = "SELECT %s FROM " . tablename('ewei_shop_order_query') . $where . $orderby;

		$limit = 'LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

		$total = pdo_fetchcolumn(sprintf($sql, 'COUNT(*)'), $param);
		$total_fee = pdo_fetchcolumn(sprintf($sql, 'SUM(total_fee)'), $param);
		$list = pdo_fetchall(sprintf($sql, '*') . $limit, $param);
		$pager = pagination($total, $pindex, $psize);

		$nofinish_apply = $this->nofinish_apply();

		load()->func('tpl');
		include $this->template('order/check');
	}

	public function nofinish_apply(){
		global $_W;
		$where = '';
		$where .= " WHERE uniacid = :uniacid";
		$param[':uniacid'] = $_W['uniacid'];

		$where .= " AND status <> :status";
		$param[':status'] = 2;

		$sql = "SELECT %s FROM " . tablename('ewei_shop_order_withdraw_apply') . $where;
		$res = pdo_fetch(sprintf($sql, '*'), $param);
		if($res){
			return true;
		}else{
			return false;
		}
	}

	public function main()
	{
		global $_W;
		global $_GPC;

		$queryData = $this->queryData(0, 'main');
	}

	public function status0()
	{
		global $_W;
		global $_GPC;
		$queryData = $this->queryData(0, 'status0');
	}

	public function status1()
	{
		global $_W;
		global $_GPC;
		$queryData = $this->queryData(1, 'status1');
	}

	public function status2()
	{
		global $_W;
		global $_GPC;
		$queryData = $this->queryData(2, 'status2');
	}

	public function status3()
	{
		global $_W;
		global $_GPC;
		$queryData = $this->queryData(3, 'status3');
	}


	public function orderquery()
	{
		global $_W;
		global $_GPC;

		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['paytype'] = 21;
		// $where['status'] = 1;
		$where['ischecked'] = 0;
		$fields = array('ordersn');
		$list = pdo_getall('ewei_shop_order',$where,$fields);

		foreach ($list as $v) {
			$query_result = $this->query_result($v['ordersn']);
			if($query_result){
				$data = array();
				$data['uniacid'] = $_W['uniacid'];
				$data['out_trade_no'] = $query_result['out_trade_no'];
				$data['transaction_id'] = $query_result['transaction_id'];
				$data['total_fee'] = $query_result['total_fee'] / 100;
				$data['time_end'] = strtotime($query_result['time_end']);
				$data['create_time'] = time();
				$id = $this->query_data_add($data);
				if($id){
					$this->order_checked($v['ordersn']);
				}
			}
		}		
		
		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['paytype'] = 21;
		$where['status'] = 1;
		$where['rechargetype'] = 'wechat';
		$where['ischecked'] = 0;
		$fields = array('logno');
		$list = pdo_getall('ewei_shop_member_log',$where,$fields);
		

		foreach ($list as $v) {
			$query_result = $this->query_result($v['logno']);
			
			if($query_result){
				$data = array();
				$data['uniacid'] = $_W['uniacid'];
				$data['out_trade_no'] = $query_result['out_trade_no'];
				$data['transaction_id'] = $query_result['transaction_id'];
				$data['total_fee'] = $query_result['total_fee'] / 100;
				$data['time_end'] = strtotime($query_result['time_end']);
				$data['create_time'] = time();
				$id = $this->query_data_add($data);
				if($id){
					$this->order_checked2($v['logno']);
				}
			}
		}

		echo json_encode(array('err_code'=>0,'msg'=>'操作成功'));		
	}
	
	public function orderquery_all123456()
	{
		global $_W;
		global $_GPC;

		if($_W['uniacid'] == 2364){
			
		
		$where = array();
		// $where['uniacid'] = $_W['uniacid'];
		// $where['paytype'] = 21;
		$where['status'] = 1;
		$where['rechargetype'] = 'wechat';
		$where['ischecked'] = 0;
		$fields = array('logno');
		$list = pdo_getall('ewei_shop_member_log',$where,$fields);
		

		foreach ($list as $v) {
			$query_result = $this->query_result($v['logno']);
			
			// var_dump($query_result);
			
			if($query_result){
				global $_W;
				$data = array();
				$data['paytype'] = 21;
				$where = array();
				$where['logno'] = $v['logno'];
				$re = pdo_update('ewei_shop_member_log',$data,$where);
			}
		}
		
		}

		echo json_encode(array('err_code'=>0,'msg'=>'操作成功'));		
	}

	public function query_result($out_trade_no){
		$check_record = $this->check_record($out_trade_no);
		if($check_record){
			return false;
		}

		include_once EWEI_SHOPV2_VENDOR . 'WxpayAPI/lib/WxPay.Api.php';
		$input = new WxPayOrderQuery();
		$input->SetOut_trade_no($out_trade_no);
		$result = WxPayApi::orderQuery($input);
		if($result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS'){
			return $result;
		}
		return false;
	}

	public function check_record($out_trade_no){
		$where = array();
		$where['out_trade_no'] = $out_trade_no;
		$res = pdo_get('ewei_shop_order_query',$where);
		if($res){
			return $res;
		}
		return false;
	}

	public function query_data_add($data){
		$re = pdo_insert('ewei_shop_order_query',$data);
		if($re){
			return pdo_insertid();
		}
		return false;
	}

	public function order_checked($ordersn){
		global $_W;
		$data = array();
		$data['ischecked'] = 1;
		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['ordersn'] = $ordersn;
		$re = pdo_update('ewei_shop_order',$data,$where);
	}
	
	public function order_checked2($ordersn){
		global $_W;
		$data = array();
		$data['ischecked'] = 1;
		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['logno'] = $ordersn;
		$re = pdo_update('ewei_shop_member_log',$data,$where);
	}

	public function check_userinfo(){
		load()->func('tpl');
		include $this->template('order/check_userinfo');
	}

	public function create_qrcode(){
		global $_W;
		if($_W['ispost']){

			load()->classs('weixin.platform');
			$arr = array(
				'key' => 'wxbe9d6a2ba6043508',
				'acid' => 747,
				'auth_refresh_token' => 'refreshtoken@@@FH1gy_G_JN99KQILsXcodyMFuCS5fXn0fGoWBULH71U',
			);
			$WeiXinPlatform = new WeiXinPlatform($arr);
			$access_token = $WeiXinPlatform->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
			$expire_seconds = 600;
			$action_name = 'QR_STR_SCENE';
			$scene_str = get_rand_str(64,2);
			$tmp = array(
				'expire_seconds' => $expire_seconds,
				'action_name' => $action_name,
				'action_info' => array(
					'scene' => array(
						'scene_str' => $scene_str,
					),
				),
			);
			$data = json_encode($tmp);
			$res = ihttp_request($url,$data);
			$arr = json_decode($res['content'],true);

			$ticket = urlencode($arr['ticket']);
			$image = '<img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket.'" width="230">';
			echo json_encode(array('err_code'=>0,'image'=>$image,'scene_str'=>$scene_str));
		}
	}

	public function ajax_query_data(){
		global $_W;
		global $_GPC;
		$where = array();
		$where['uniacid'] = 765;
		$where['scene_str'] = $_GPC['scene_str'];
		$row = pdo_get('qrcode_stat',$where);
		if($row){
			echo json_encode(array('err_code'=>0,'openid'=>$row['openid']));
		}else{
			echo json_encode(array('err_code'=>-1));
		}
	}

	public function withdraw_apply(){
		global $_W;
		global $_GPC;

		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['status'] = 0;
		
		$list = pdo_getall('ewei_shop_order_query', $where,array('id','total_fee'));
		if(empty($list)){
			echo json_encode(array('err_code'=>1000,'msg'=>'没有未对账的订单!'));
			exit;
		}
		$ids = array();
		$total_money = 0;
		foreach ($list as $v) {
			$ids[] = $v['id'];
			$total_money += $v['total_fee'];
		}

		$real_name = $_GPC['postdata']['real_name'];
		if(empty($real_name)){
			echo json_encode(array('err_code'=>1000,'msg'=>'请填写姓名!'));
			exit;
		}

		$id_number = $_GPC['postdata']['id_number'];
		if(empty($id_number)){
			echo json_encode(array('err_code'=>1000,'msg'=>'请填写身份证号码!'));
			exit;
		}

		$openid = $_GPC['postdata']['openid'];
		if(empty($openid)){
			echo json_encode(array('err_code'=>1000,'msg'=>'请填写扫描二维码获取openid!'));
			exit;
		}

		$data = array();
		$data['status'] = 1;
		$data['apply_time'] = time();

		$where = array();
		$where['uniacid'] = $_W['uniacid'];
		$where['status'] = 0;
		$where['id'] = $ids;
		$re = pdo_update('ewei_shop_order_query',$data, $where);

		if($re){
			$arr = array();
			$arr['uniacid'] = $_W['uniacid'];
			$arr['apply_ids'] = implode(',', $ids);
			$arr['real_name'] = $real_name;
			$arr['id_number'] = $id_number;
			$arr['openid'] = $openid;
			$arr['total_money'] = $total_money;
			$arr['create_time'] = time();
			pdo_insert('ewei_shop_order_withdraw_apply',$arr);

			echo json_encode(array('err_code'=>0,'msg'=>'操作成功!'));
		}else{
			echo json_encode(array('err_code'=>1000,'msg'=>'操作失败!'));
		}

	}


	public function apply_list(){
		global $_W;
		global $_GPC;


		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$where = '';
		$where .= " WHERE uniacid = :uniacid";
		$param[':uniacid'] = $_W['uniacid'];

		$orderby = " order by id desc ";
		
		$sql = "SELECT %s FROM " . tablename('ewei_shop_order_withdraw_apply') . $where . $orderby;

		$limit = 'LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

		$total = pdo_fetchcolumn(sprintf($sql, 'COUNT(*)'), $param);
		$list = pdo_fetchall(sprintf($sql, '*') . $limit, $param);
		$pager = pagination($total, $pindex, $psize);

		load()->func('tpl');
		include $this->template('order/check_apply');
	}

	public function apply_detail(){
		global $_W;
		global $_GPC;

		$id = $_GPC['id'];

		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$row = pdo_get('ewei_shop_order_withdraw_apply',array('id'=>$id));
		$condition['id'] = $row['apply_ids'];

		$condition['uniacid'] = $_W['uniacid'];

		if (!(empty($_GPC['searchfield'])) && !(empty($_GPC['keyword']))) {
			$searchfield = trim(strtolower($_GPC['searchfield']));
			$_GPC['keyword'] = trim($_GPC['keyword']);

			if ($searchfield == 'out_trade_no') {
				$condition['out_trade_no'] = $_GPC['keyword'];
				
			}
		}

		$detail_list = $this->apply_detail_list($condition, array($pindex, $psize));
		$list = $detail_list['list'];

		$total = $detail_list['total'];
		$pager = pagination($total, $pindex, $psize);

		load()->func('tpl');
		include $this->template('order/apply_detail');
	}

	public function apply_detail_list($condition = array(), $paper = array()) {
		global $_W;

		$sql = "SELECT %s FROM " . tablename('ewei_shop_order_query') . " WHERE 1";	

		if (!empty($condition['status'])) {
			$sql .= " AND status = :status";
			$param[':status'] = $condition['status'];
		}

		if (!empty($condition['uniacid'])) {
			$sql .= " AND uniacid = :uniacid";
			$param[':uniacid'] = $condition['uniacid'];
		}

		if (!empty($condition['out_trade_no'])) {
			$sql .= " AND out_trade_no like :out_trade_no";
			$param[':out_trade_no'] = $condition['out_trade_no'];
		}

		if (!empty($condition['id'])) {
			$sql .= " AND id in (" . $condition['id'] . ")";
		}else{
			return array(
				'list' => array(),
				'total' => 0,
			);
		}

		$sql .= " order by id desc ";
		if($paper){

			$limit = " LIMIT " . ($paper[0] - 1) * $paper[1] . "," . $paper[1];
		}

		$list = pdo_fetchall(sprintf($sql, '*') . $limit, $param);
		$total = pdo_fetchcolumn(sprintf($sql, 'COUNT(*)'), $param);

		return array(
			'list' => $list,
			'total' => $total,
		);
	}

}


?>