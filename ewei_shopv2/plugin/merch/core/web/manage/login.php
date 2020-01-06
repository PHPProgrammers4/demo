<?php
/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */
class Login_EweiShopV2Page extends Page {

      function main(){
          global $_W,$_GPC;
          $i = intval($_GPC['i']);          
          if(empty($i)){
            $_W['uniacid'] = $_COOKIE[$_W['config']['cookie']['pre'].'__uniacid'];//$_SESSION['__merch_uniacid'];//
          }else{
            $_W['uniacid'] = $i;
          }
          $_SESSION['__merch_uniacid'] = $_W['uniacid'];
          //$set = p('merch')->getPluginsetByMerch('merch');
          $set = m('common')->getPluginset('merch', $_W['uniacid']);

          if($_W['ispost']){
              $username = trim($_GPC['username']);
              $pwd = trim($_GPC['pwd']);
              if(empty($username)){
                  show_json(0,'请输入用户名!');
              }
              if(empty($pwd)){
                  show_json(0,'请输入密码!');
              }
              $account =pdo_fetch("select * from ".tablename('ewei_shop_merch_account')." where uniacid=:uniacid and username=:username limit 1",array(':uniacid'=>$_W['uniacid'],':username'=>$username));
              if(empty($account)){
                  show_json(0,'用户未找到!');
              }

              $pwd = md5($pwd.$account['salt']);
              if($account['pwd']!=$pwd){
                    show_json(0,'用户密码错误!');
              }

              $user = pdo_fetch('select status,accounttime from ' . tablename('ewei_shop_merch_user') . ' where uniacid=:uniacid and accountid=:accountid limit 1', array(':uniacid'=>$_W['uniacid'],':accountid' => $account['id']));

              //商户过期处理
              if ((int)$user['accounttime'] > 0) {
                  if ((int)$user['accounttime'] <= time()) {
                      show_json(0,'账号已过期，请联系商家咨询!');
                  }
              }

              if (!empty($user)) {
                  if($user['status'] == 2){
                      show_json(0,'帐号暂停中,请联系管理员!');
                  }
              }

              $account['hash'] = md5($account['pwd'] . $account['salt']);
              $session = base64_encode(json_encode($account));
              $session_key = '__merch_'.$account['uniacid'].'_session';
              isetcookie($session_key, $session,0, true);

              $data = m('common')->getPluginset('merch');
              $member = array('username'=>$data['temporaryusername'],'password'=>$data['temporarypassword']);
              load()->model('user');
              $record = user_single($member);
              $cookie = array();
              $cookie['uid'] = $record['uid'];
              $cookie['lastvisit'] = $record['lastvisit'];
              $cookie['lastip'] = $record['lastip'];
              $cookie['hash'] = !empty($record['hash']) ? $record['hash'] : md5($record['password'] . $record['salt']);
              $cookie['rember'] = safe_gpc_int($_GPC['rember']);
              $session = authcode(json_encode($cookie), 'encode');
              $autosignout = (int)$_W['setting']['copyright']['autosignout'] > 0 ? (int)$_W['setting']['copyright']['autosignout'] * 60 : 0;
              isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : $autosignout, true);
              
              $status = array();
              $status['lastvisit'] = TIMESTAMP;
              $status['lastip'] = CLIENT_IP;
              pdo_update('ewei_shop_merch_account',$status,array('id'=>$account['id']));
              $url = $_W['siteroot']."web/merchant.php?c=site&a=entry&i={$account['uniacid']}&m=ewei_shopv2&do=web&r=shop";
              show_json(1,array('url'=>$url));

          }
          $submitUrl =$_W['siteroot']."web/merchant.php?c=site&a=entry&i={$_COOKIE[$_W['config']['cookie']['pre'].'__uniacid']}&m=ewei_shopv2&do=web&r=login";


          $set['regpic'] = $this->getImage( $set , 'regpic' );
          $set['reglogo'] = $this->getImage( $set , 'reglogo' );
          include $this->template('merch/manage/login');


      }

      private function getImage( $set ,$f)
      {
          global $_W;
          $remote = $_W['setting']['remote'];
          $uniacid = $_W['uniacid'];
          $url = $set[$f];
          if(empty($url)){
              return '';
          }
          //处理公众号自己设置开启远程附件
          if(!empty($remote[$uniacid])){
              return $this->takeUrl( $url ,$remote[$uniacid] );
          }else{
              return $this->takeUrl( $url );
          }
      }
    //获取多商户远程附件
    private function takeUrl( $url ,$remote = [])
    {
        global $_W;
        if( strexists($url,"http://") || strexists($url,"https://") ){
            return $url;
        }
        if(empty($remote)){
            $remote = $_W['setting']['remote'];
        }
        $type = $remote['type'];
        $typeStr = '';
        switch ( $type ){
            case 1 :
                $typeStr = 'ftp';
                break;
            case 2:
                $typeStr = 'alioss';
                break;
            case 3:
                $typeStr = 'qiniu';
                break;
            case 4:
                $typeStr = 'cos';
                break;
            default:
                continue;
        }
        return $remote[$typeStr]['url'].'/'.$url;

    }

}