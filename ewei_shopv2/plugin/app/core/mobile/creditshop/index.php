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

class Index_EweiShopV2Page extends AppMobilePage
{
    function main()
    {
        global $_W, $_GPC;

//        $this->diyPage('creditshop');
        //多商户
        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }
        $contation = " uniacid=:uniacid ";
        if (intval($_GPC['merchid']) > 0) {
            $contation .= "and merchid = " . intval($_GPC['merchid']) . " ";
        }
        $data = array();
        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $shop = m('common')->getSysset('shop');

        //广告
        $advs = pdo_fetchall("select id,advname,link,thumb from " . tablename('ewei_shop_creditshop_adv') . ' where ' . $contation . ' and enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
        $advs = set_medias($advs, 'thumb');
        $data['advs'] = $advs;

        //当前积分
//        $credit = m('member')->getCredit($openid, 'credit1');
//        //分类
        $category = array();
        if (intval($_GPC['merchid']) > 0) {
            $merch_category = p('merch')->getSet('merch_creditshop_category', $_GPC['merchid']);
            if (!empty($merch_category)) {
                $i = 0;
                foreach ($merch_category as $index => $row) {
                    if ($row > 0) {
                        $list = pdo_fetch("select id,name,thumb,isrecommand from " . tablename('ewei_shop_creditshop_category') . '
						where id = ' . $index . ' and uniacid=:uniacid and  enabled=1 ', array(':uniacid' => $uniacid));
                        $list = set_medias($list, 'thumb');
                        $category[$i] = $list;
                        $i++;
                    }
                }
            }
        } else {
            $category = pdo_fetchall("select id,name,thumb,isrecommand from " . tablename('ewei_shop_creditshop_category') . ' where uniacid=:uniacid and  enabled=1 order by displayorder desc', array(':uniacid' => $uniacid));
            $category = set_medias($category, 'thumb');
        }
        array_values($category);
        $data['category'] = $category;
        //积分抽奖
        $lotterydraws = pdo_fetchall("select id, title,goodstype, subtitle, credit, money, thumb,`type`,price from " . tablename('ewei_shop_creditshop_goods') . '
				where ' . $contation . ' and isrecommand = 1 and `type` = 1 and  status=1 and deleted=0 order by displayorder,id desc limit 4', array(':uniacid' => $uniacid));
        $lotterydraws = set_medias($lotterydraws, 'thumb');
        is_array($lotterydraws) ? $lotterydraws : $lotterydraws = array();
        foreach ($lotterydraws as $key => $value) {
            $lotterydraws[$key]['money'] = price_format($value['money'],2);
        }
        $data['lotterydraws'] = $lotterydraws;

        //积分兑换
        $exchanges = pdo_fetchall("select id, title,goodstype, subtitle, credit, money, thumb,`type` from " . tablename('ewei_shop_creditshop_goods') . '
				where ' . $contation . ' and isrecommand = 1 and goodstype = 0 and `type` = 0 and status=1 and deleted=0 order by id,displayorder desc limit 4', array(':uniacid' => $uniacid));
        $exchanges = set_medias($exchanges, 'thumb');
        is_array($exchanges) ? $exchanges : $exchanges = array();
        foreach ($exchanges as $key => $value) {
            $exchanges[$key]['money'] = price_format($value['money'],2);
        }
        $data['exchanges'] = $exchanges;

        //优惠券兑换
        $coupons = pdo_fetchall("select id, title, subtitle, credit, money, thumb,`type` from " . tablename('ewei_shop_creditshop_goods') . '
				where ' . $contation . ' and isrecommand = 1 and goodstype = 1 and `type` = 0 and status=1 and deleted=0 order by id,displayorder desc limit 4', array(':uniacid' => $uniacid));
        $coupons = set_medias($coupons, 'thumb');
        is_array($coupons) ? $coupons : $coupons = array();
        foreach ($coupons as $key => $value) {
            $coupons[$key]['money'] = price_format($value['money'],2);
        }
        $data['coupons'] = $coupons;

        //余额兑换
        $balances = pdo_fetchall("select id, title, subtitle, credit, money, thumb,`type` from " . tablename('ewei_shop_creditshop_goods') . '
				where ' . $contation . ' and isrecommand = 1 and goodstype = 2 and `type` = 0 and status=1 and deleted=0 order by id,displayorder desc limit 4', array(':uniacid' => $uniacid));
        $balances = set_medias($balances, 'thumb');
        is_array($balances) ? $balances : $balances = array();
        foreach ($balances as $key => $value) {
            $balances[$key]['money'] = price_format($value['money'],2);
        }
        $data['balances'] = $balances;

        //红包兑换
        $redbags = pdo_fetchall("select id, title, subtitle, credit, money, thumb,`type` from " . tablename('ewei_shop_creditshop_goods') . '
				where ' . $contation . ' and isrecommand = 1 and goodstype = 3 and `type` = 0 and  status=1 and deleted=0 order by id,displayorder desc limit 4', array(':uniacid' => $uniacid));
        $redbags = set_medias($redbags, 'thumb');
        is_array($redbags) ? $redbags : $redbags = array();
        foreach ($redbags as $key => $value) {
            $redbags[$key]['money'] = price_format($value['money'],2);
        }
        $data['redbags'] = array();


        /* 分享 *************/
//        $member = m('member')->getMember($openid);
//        $_W['shopshare'] = array(
//            'title' => $this->set['share_title'],
//            'imgUrl' => tomedia($this->set['share_icon']),
//            'link' => mobileUrl('creditshop', array(), true),
//            'desc' => $this->set['share_desc']
//        );
//        $com = p('commission');
//        if ($com) {
//            $cset = $com->getSet();
//            if (!empty($cset)) {
//                if ($member['isagent'] == 1 && $member['status'] == 1) {
//                    $_W['shopshare']['link'] = mobileUrl('creditshop', array('mid' => $member['id']), true);
//                    if (empty($cset['become_reg']) && (empty($member['realname']) || empty($member['mobile']))) {
//                        $trigger = true;
//                    }
//                } else if (!empty($_GPC['mid'])) {
//                    $_W['shopshare']['link'] = mobileUrl('creditshop/detail', array('mid' => $_GPC['mid']), true);
//                }
//            }
//        }
        return app_json(array('data' => $data , 'is_openmerch' => $is_openmerch));
    }
}