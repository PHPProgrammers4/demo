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
function app_error($errcode = 0,$message = '') {
    return json_encode(array(
        'error'=>$errcode,
        'message'=>empty($message)?AppError::getError( $errcode ):$message
    ));

}

function app_json($result = null,$openid) {
    global $_GPC,$_W;
    $ret = array();
    if(!is_array($result)){
        $result = array();
    }
    $ret['error'] = 0;
    $key = time().'@'.$openid;
    $auth =array('authkey'=>base64_encode(authcode($key,'ENCODE', "ewei_shopv2_wxapp")));
    m('cache')->set($auth['authkey'],1);
    return json_encode( array_merge($ret,$auth,$result));
}
require EWEI_SHOPV2_PLUGIN . 'app/core/error_code.php';
require EWEI_SHOPV2_PLUGIN . 'app/core/wxapp/wxBizDataCrypt.php';


class Wxapp_EweiShopV2Page extends Page
{
    protected $appid;
    protected $appsecret;

    public function __construct()
    {
        $data = m('common')->getSysset('app');
        $this->appid = $data['appid'];
        $this->appsecret = $data['secret'];
    }

    public function login() {
        global $_GPC,$_W;

        $code = trim($_GPC['code']);
        if (empty($code)) {
            return app_error(AppError::$ParamsError);
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->appsecret}&js_code={$code}&grant_type=authorization_code";
        
        load()->func('communication');
        $resp = ihttp_request($url);

        if(is_error($resp)){
            return app_error(AppError::$SystemError , $resp['message']);;
        }
        $arr = @json_decode($resp['content'],true);
        $arr['isclose'] = $_W['shopset']['app']['isclose'];
        if(!empty($_W['shopset']['app']['isclose'])){
            $arr['closetext'] = $_W['shopset']['app']['closetext'];
        }

        if(!is_array($arr) || !isset($arr['openid'])){
            return app_error(AppError::$WxAppLoginError);
        }
        return app_json($arr,$arr['openid']);

    }

    /**
     * 微信小程序登录
     */
    public function auth() {
        global $_GPC,$_W;

        $encryptedData = trim($_GPC['data']);
        $iv = trim($_GPC['iv']);
        $sessionKey = trim($_GPC['sessionKey']);
        if (empty($encryptedData) || empty($iv)) {
            return app_error(AppError::$ParamsError);
        }
        $pc = new WXBizDataCrypt($this->appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data,true);
            // sns_wa 小程序
            //  sns_wx APP微信
            //  sns_qq APP QQ
            $this->refine($data['openId']);
            $member = m('member')->getMember('sns_wa_'.$data['openId']);
            if (empty($member)){
                $member = array(
                    'uniacid' => $_W['uniacid'],
                    'uid' => 0,
                    'openid' => 'sns_wa_'.$data['openId'],
                    'nickname' => !empty($data['nickName']) ? $data['nickName'] : '',
                    'avatar' => !empty($data['avatarUrl']) ? $data['avatarUrl'] : '',
                    'gender' => !empty($data['gender']) ? $data['gender'] : '-1',
//                'province' => !empty($arr['province']) ? $arr['province'] : '',
//                'city' => !empty($arr['city']) ? $arr['city'] : '',
//                'area' => !empty($arr['residedist']) ? $arr['residedist'] : '',
                    'openid_wa' =>$data['openId'],
                    'comefrom' =>'sns_wa',
                    'createtime' => time(),
                    'status' => 0
                );
                pdo_insert('ewei_shop_member', $member);
                $id = pdo_insertid();
                $data['id'] = $id;
                $data['uniacid'] = $_W['uniacid'];
                if(method_exists(m('member'),'memberRadisCountDelete')) {
                    m('member')->memberRadisCountDelete(); //清除会员统计radis缓存
                }
            }else{
                $updateData = array(
                    'nickname' => !empty($data['nickName']) ? $data['nickName'] : '',
                    'avatar' => !empty($data['avatarUrl']) ? $data['avatarUrl'] : '',
                    'gender' => !empty($data['gender']) ? $data['gender'] : '-1',
                );
                pdo_update('ewei_shop_member',$updateData,array('id'=>$member['id'],'uniacid'=>$member['uniacid']));
                $data['id'] = $member['id'];
                $data['uniacid'] = $member['uniacid'];
                $data['isblack'] = $member['isblack'];
            }
            if (p('commission')) {
                p('commission')->checkAgent($member['openid']);
            }
            return app_json($data,$data['openId']);
        }
        return app_error(AppError::$WxAppError , '登录错误, 错误代码: '.$errCode);
    }

    /**
     * 处理注册用户openid多生成一个sns_wa_前缀的问题
     * 例如:
     *   正常:sns_wa_oX-v90Cdn4BpQSByrQZgS8dKLK_w
     *   异常:sns_wa_sns_wa_oX-v90Cdn4BpQSByrQZgS8dKLK_w
     * @param $openid string
     */
    protected function refine(&$openid)
    {
        if (substr($openid, 0, 7) == 'sns_wa_') {
            $openid = substr($openid, 7);
        }
    }
}