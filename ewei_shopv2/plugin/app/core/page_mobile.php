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
require_once EWEI_SHOPV2_PLUGIN . 'app/core/error_code.php';
$iswxapp = false;
$openid = '';
function filterEmptyData($result) {
    foreach ($result as $k=>$v){
        if ((empty($v)&&is_array($v)) || $v ===NULL){
            unset($result[$k]);
            continue;
        }
        if (is_array($v)){
            $result[$k] = filterEmptyData($v);
        }
    }
    return $result;
}

function app_error($errcode = 0,$message = '') {
    global $iswxapp,$openid;
    $res = array(
        'error'=>$errcode,
        'message'=>empty($message)?AppError::getError( $errcode ):$message
    );
    /*
    if ($iswxapp){
        $key = time().'@'.$openid;
        $authkey = base64_encode(authcode($key,'ENCODE', "ewei_shopv2_wxapp"));
        m('cache')->set($authkey,1);
        $res['authkey'] = $authkey;
    }*/
	return json_encode($res);
}

function app_json($result = null) {

    global $iswxapp,$openid,$_W,$_GPC;
	$ret = array();
	if(!is_array($result)){
		$result = array();
	}
	$ret['error'] = 0;
	if (!empty($result) && !$iswxapp)
    {
        $result = filterEmptyData($result);
    }
    /*
    if ($iswxapp && false){
        $key = time().'@'.$openid;
        $authkey = base64_encode(authcode($key,'ENCODE', "ewei_shopv2_wxapp"));
        m('cache')->set($authkey,1);
        $ret['authkey'] = $authkey;
    }*/


    $ret['sysset'] = array(
        'shopname' => $_W['shopset']['shop']['name'],
        'shoplogo' => $_W['shopset']['shop']['logo'],
        'description' => $_W['shopset']['shop']['description'],
        'saleout_icon'  => isset($_W['shopset']['shop']['saleout']) ? tomedia($_W['shopset']['shop']['saleout']) : '',
        'share' => $_W['shopset']['share'],
        'texts' => array(
            'credit' => $_W['shopset']['trade']['credittext'],
            'money' => $_W['shopset']['trade']['moneytext']
        ),
        'isclose' => $_W['shopset']['app']['isclose'],
        'force_auth' => isset($_W['shopset']['app']['force_auth']) ? $_W['shopset']['app']['force_auth'] : 0
    );

    $ret['sysset']['share']['logo'] = tomedia($ret['sysset']['share']['logo']);
    $ret['sysset']['share']['icon'] = tomedia($ret['sysset']['share']['icon']);
    $ret['sysset']['share']['followqrcode'] = tomedia($ret['sysset']['share']['followqrcode']);
    if (!empty($_W['shopset']['app']['isclose'])) {
        $ret['sysset']['closetext'] = $_W['shopset']['app']['closetext'];
    }
    
	return json_encode( array_merge($ret,$result));
}
/** Json数据格式化
 * @param  Mixed  $data   数据
 * @param  String $indent 缩进字符，默认4个空格
 * @return JSON
 */
function jsonFormat($data, $indent=null){

	// 对数组中每个元素递归进行urlencode操作，保护中文字符
	array_walk_recursive($data, 'jsonFormatProtect');

	// json encode
	$data = json_encode($data);

	// 将urlencode的内容进行urldecode
	$data = urldecode($data);

	// 缩进处理
	$ret = '';
	$pos = 0;
	$length = strlen($data);
	$indent = isset($indent)? $indent : '    ';
	$newline = "\n";
	$prevchar = '';
	$outofquotes = true;

	for($i=0; $i<=$length; $i++){

		$char = substr($data, $i, 1);

		if($char=='"' && $prevchar!='\\'){
			$outofquotes = !$outofquotes;
		}elseif(($char=='}' || $char==']') && $outofquotes){
			$ret .= $newline;
			$pos --;
			for($j=0; $j<$pos; $j++){
				$ret .= $indent;
			}
		}

		$ret .= $char;

		if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
			$ret .= $newline;
			if($char=='{' || $char=='['){
				$pos ++;
			}

			for($j=0; $j<$pos; $j++){
				$ret .= $indent;
			}
		}

		$prevchar = $char;
	}

	return $ret;
}

/** 将数组元素进行urlencode
 * @param String $val
 */
function jsonFormatProtect(&$val){
	if($val!==true && $val!==false && $val!==null){
		$val = urlencode($val);
	}
}

class AppMobilePage extends PluginMobilePage {
    protected $member;
    protected $iswxapp=false;
	public function __construct() {
		global $_GPC,$_W,$iswxapp,$openid;

        $this->model = m('plugin')->loadModel($GLOBALS["_W"]['plugin']);
        $this->set = $this->model->getSet();
        if ( ($_GPC['r']!='app.cacheset' && strexists($_GPC['openid'],'sns_wa')) || (isset($_GPC['comefrom']) && $_GPC['comefrom'] == 'wxapp') ){
            $iswxapp = true;
            $this->iswxapp = true;
            /*
            if (empty($_GPC['authkey'])){
                return app_error(AppError::$SystemError,'授权码出错1!');
            }
            $authkey = m('cache')->getString($_GPC['authkey']);

            if (empty($authkey) && $authkey != 1){
                return app_error(AppError::$SystemError,'授权码出错2!');
            }
            m('cache')->del($_GPC['authkey']);*/
        }
        if($_GPC['openid'] != 'sns_wa_'){
            //pc 赋值openid
            if(empty($_GPC['openid'])){
                $_GPC['openid'] = $_W['openid'];
            }

            $member= m('member')->getMember($_GPC['openid']);
            $this->member = $member;

            //分销商
            if (p('commission')) {
                p('commission')->checkAgent($member['openid']);
            }

            $GLOBALS['_W']['openid'] = $_W['openid'] = $member['openid'];
            if($this->iswxapp){
                $GLOBALS['_W']['openid_wa'] = $_W['openid_wa'] = 'sns_wa_'. $member['openid_wa'];
            }
        }


        //整体配置
		$global_set= m('cache')->getArray('globalset','global');
		if(empty($global_set)){
			$global_set = m('common')->setGlobalSet($_W['uniacid']);
		}
		if(!is_array($global_set)){
			$global_set = array();
		}
		empty($global_set['trade']['credittext']) && $global_set['trade']['credittext'] = "积分";
		empty($global_set['trade']['moneytext']) && $global_set['trade']['moneytext'] = "余额";
		$GLOBALS["_W"]['shopset']  = $global_set;

	}

	public function logging($message = '') {
		$filename = IA_ROOT . '/data/logs/' . date('Ymd') . '.php';
		load()->func('file');
		mkdirs(dirname($filename));
		$content = date('Y-m-d H:i:s') . " \n------------\n";
		if(is_string($message) && !in_array($message, array('post', 'get'))) {
			$content .= "String:\n{$message}\n";
		}
		if(is_array($message)) {
			$content .= "Array:\n";
			foreach($message as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		if($message === 'get') {
			$content .= "GET:\n";
			foreach($_GET as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		if($message === 'post') {
			$content .= "POST:\n";
			foreach($_POST as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		$content .= "\n";
		$filename = IA_ROOT . '/data/logs/' . date('Ymd') . '.log';
		$fp = fopen($filename, 'a+');
		fwrite($fp, $content);
		fclose($fp);
	}

    /**
     * 检测绑定Uniacid
     */
	private function checkUniacid() {
	    global $_W, $_GPC;

        if(empty($_GPC['formwe7'])){
            //return;
        }

        $bind = pdo_fetch('SELECT * FROM '. tablename('ewei_shop_wxapp_bind'). ' WHERE wxapp=:wxapp LIMIT 1', array(':wxapp'=>$_W['uniacid']));
        if(!empty($bind)){
            $GLOBALS['_W']['uniacid'] = $GLOBALS['_W']['acid'] = $bind['uniacid'];
        }
    }

}
