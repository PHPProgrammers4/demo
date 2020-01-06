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

class Index_EweiShopV2Page extends PluginWebPage {
	public function main() {
	    global $_W;


        $this->model->apiFile();

        if(empty($_W['shopversion'])){
            $this->message('请使用新版本访问');
        }

        $error = null;

        // 获取授权状态
        $auth = $this->model->getAuth();
        if (is_error($auth)){
            $error = $auth['message'];
        } else {
            $list = $this->model->getReleaseList();
            if(is_error($list)){
                $error = $list['message'];
            }
            elseif(empty($list)){
                $error = '未查询到授权小程序';
            }
        }

        //旧版发布已经关闭，强制跳转至新版发布
//        header('location: ' . webUrl('app.newrelease'));
//        exit();

        include $this->template();
	}

    public function syswxapp() {
        global $_W, $_GPC;

        $hasSysWxapp = @is_file(IA_ROOT. '/addons/ewei_shopwxapp/wxapp.php');
        if(!$hasSysWxapp){
            $this->message('未安装系统小程序');
        }

        $wxapp_list = pdo_fetchall('SELECT we.uniacid, we.name, bind.uniacid as uniacid_account, bind.wxapp as uniacid_wxapp FROM '. tablename('account_wxapp'). ' we LEFT JOIN '. tablename('ewei_shop_wxapp_bind'). ' bind ON bind.wxapp=we.uniacid WHERE bind.id IS NULL OR bind.uniacid=:uniacid ', array(
            ':uniacid'=>$_W['uniacid']
        ));

        if($_W['ispost']){
            $uniacid = intval($_GPC['uniacid']);
            $bind = pdo_fetch('SELECT * FROM '. tablename('ewei_shop_wxapp_bind'). ' WHERE uniacid=:uniacid LIMIT 1', array(':uniacid'=>$_W['uniacid']));
            if(empty($bind)){
                pdo_insert('ewei_shop_wxapp_bind', array('uniacid'=>$_W['uniacid'], 'wxapp'=>$uniacid));
            }else{
                pdo_update('ewei_shop_wxapp_bind', array('uniacid'=>$_W['uniacid'], 'wxapp'=>$uniacid), array('id'=>$bind['id']));
            }

            show_json(1);
        }

        include $this->template();
    }
}