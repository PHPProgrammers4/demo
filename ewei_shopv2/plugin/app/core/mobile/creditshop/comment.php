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

require_once EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class Comment_EweiShopV2Page extends AppMobilePage
{

    public function __construct()
    {
        parent::__construct();
        $trade = m('common')->getSysset('trade');
        if ( !empty($trade['closecomment']) )
        {
            $this->message('不允许评论!','','error');
            return app_error( AppError::$OrderCanNotComment , '不允许评论!' );
        }
    }


    public function main() {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $openid = $_W['openid'];
        $goodsid = intval($_GPC['goodsid']);
        $logid = intval($_GPC['logid']);
        //多商户
        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }
        $merchid = intval($_GPC['merchid']);
        $condition = " log.uniacid = ".$uniacid." ";
        if($merchid>0){
            $condition .= " and g.merchid = ".$merchid." ";
        }
        //评论
        $log = pdo_fetch("select log.id,log.status,log.goodsid,log.goods_num,log.merchid,g.goodstype,g.type,log.iscomment,log.optionid,g.thumb,o.title as optiontitle,g.title
                ,g.credit,g.money
                from ".tablename('ewei_shop_creditshop_log')." as log
                left join ".tablename('ewei_shop_creditshop_goods')." as g on g.id = log.goodsid
                left join " . tablename('ewei_shop_creditshop_option') . " o on o.id=log.optionid
                where ".$condition." and log.goodsid = ".$goodsid." and log.id = ".$logid." ");
        if (empty($log)) {
            return app_error(AppError::$ParamsError, '订单不存在或已删除!');
        }
        $log = set_medias($log, 'thumb');
        $log['money'] = print_r($log['money'],2);

        if($log['goodstype']==0){//商品
            if ($log['status'] != 3) {
                return app_error(AppError::$OrderNndone,'订单未完成，不能评价!');
            }
        }elseif($log['goodstype']==1){//优惠券
            if ($log['status'] != 3) {
                return app_error(AppError::$OrderNndone,'订单未完成，不能评价!');
            }
        }elseif($log['goodstype']==2){//余额
            if ($log['status'] != 3) {
                return app_error(AppError::$OrderNndone,'订单未完成，不能评价!');
            }
        }elseif($log['goodstype']==3){//红包
            if ($log['status'] !=3) {
                return app_error(AppError::$OrderNndone,'订单未完成，不能评价!');
            }
        }
        if ($log['iscomment'] >= 2) {
            $this->message('',mobileUrl('creditshop/log/detail',array('id'=>$logid)));
            return app_error(AppError::$OrderNndone,'您已经评价过了!');
        }

        return app_json(array('is_openmerch' => $is_openmerch , 'log' => $log,'shop'=>$_W['shopset']['shop']));
    }

    public function submit() {

        global $_W, $_GPC;
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $logid = intval($_GPC['logid']);
        $merchid = intval($_GPC['merchid']);
        $condition = " and uniacid=:uniacid ";
        if($merchid>0){
            $condition .= " and merchid = ".$merchid." ";
        }
        //评论
        $log = pdo_fetch("select id,status,iscomment,logno from " . tablename('ewei_shop_creditshop_log') . ' where id=:id '.$condition.' and openid=:openid limit 1'
            , array(':id' => $logid, ':uniacid' => $uniacid, ':openid' => $openid));
        if (empty($log)) {
            return app_error(AppError::$RecordNotFound, '兑换记录未找到');
        }

        $member = m('member')->getMember($openid);
        $comments = $_GPC['comments'];
        if (!is_array($comments)) {
            return app_error(AppError::$RecordNotFound, '数据出错，请重试!');
        }

        $trade = m('common')->getSysset('trade');
        if (!empty($trade['commentchecked'])) {
            $checked = 0;
        } else {
            $checked = 1;
        }

        foreach ($comments as $c) {
            $old_c = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_creditshop_comment') . '
            where uniacid=:uniacid and logid=:logid and goodsid=:goodsid limit 1', array(':uniacid' => $_W['uniacid'], ':goodsid' => $c['goodsid'], ':logid' => $logid));
            if (empty($old_c)) {
                //第一次评论
                $comment = array(
                    'uniacid' => $uniacid,
                    'logid' => $logid,
                    'logno' => $log['logno'],
                    'goodsid' => $c['goodsid'],
                    'level' => $c['level'],
                    'content' => trim($c['content']),
                    'images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()),
                    'openid' => $openid,
                    'nickname' => $member['nickname'],
                    'headimg' => $member['avatar'],
                    'time' => time(),
                    'checked' => $checked
                );
                pdo_insert('ewei_shop_creditshop_comment', $comment);
            } else {
                $comment = array(
                    'append_content' => trim($c['content']),
                    'append_images' => is_array($c['images']) ? iserializer($c['images']) : iserializer(array()),
                    'append_checked' => $checked,
                    'append_time' => time()
                );
                pdo_update('ewei_shop_creditshop_comment', $comment, array('uniacid' => $_W['uniacid'], 'goodsid' => $c['goodsid'], 'logid' => $logid));
            }
        }
        if ($log['iscomment'] <= 0) {
            $d['iscomment'] = 1;
        } else {
            $d['iscomment'] = 2;
        }
        pdo_update('ewei_shop_creditshop_log', $d, array('id' => $logid, 'uniacid' => $uniacid));
        return app_json();
    }


}