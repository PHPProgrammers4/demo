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

class Selecturl_EweiShopV2Page extends WebPage {

    protected $full = false;
    protected $platform = false;

    function __construct(){
        global $_W, $_GPC;

        $this->full = intval($_GPC['full']);
        $this->platform = trim($_GPC['platform']);
        $this->defaultUrl = trim($_GPC['url']);
    }


    function main() {

		global $_W, $_GPC;
		$syscate = m('common')->getSysset('category');

		if(isset($_GPC['type']) && !empty($_GPC['type'])){
            $type = $_GPC['type'];
        }
		

		if($syscate['level']>0){
			$categorys = pdo_fetchall("SELECT id,name,parentid FROM " . tablename('ewei_shop_category') . " WHERE enabled=:enabled and uniacid= :uniacid  ", array(':uniacid' => $_W['uniacid'], ':enabled' => '1'));
		}

        $groups = pdo_fetchall("SELECT id,name FROM " .tablename('ewei_shop_goods_group'). " WHERE enabled=:enabled AND merchid = 0 AND  uniacid= :uniacid ", array(':uniacid' => $_W['uniacid'],':enabled' => '1'));

		$storeList = pdo_fetchall("SELECT id,storename FROM " .tablename('ewei_shop_store') ." WHERE status = 1 AND uniacid = :uniacid ",array(':uniacid' => $_W['uniacid']));

		if(p('diypage')) {
		    if($type == 'topmenu'){
                $diypage = p('diypage')->getPageList('allpage', ' and (`type` = 1 or `type` = 2)');
                if(!empty($diypage)) {
                    foreach($diypage['list'] as $k => $v){
                        $pages = json_decode(base64_decode($v['data']),true);
                        foreach($pages['items'] as $pk => $pv){
                            if($pv['id'] == 'topmenu'){
                                unset($diypage['list'][$k]);
                            }
                        }
                    }
                }
                $allpagetype = p('diypage')->getPageType();
            }else{
                $diypage = p('diypage')->getPageList('allpage', ' and `type`<5');
                $allpagetype = p('diypage')->getPageType();
            }
		}

        $platform = $this->platform;

        if(p('quick')) {
            if($platform == 'wxapp' || $platform == 'wxapp_tabbar'){
                $quickList = p('quick')->getPageList('',1, ' status=1 and ');
            }else{
                $quickList = p('quick')->getPageList();
            }
        }

        if(p('friendcoupon')) {
            $activities = p('friendcoupon')->getActivityList();
        }


        $full = $this->full;

        if($platform=='wxapp' && !empty($this->defaultUrl) && strexists($this->defaultUrl, '/pages/web/index')){
            $defaultUrl = urldecode($this->defaultUrl);
            $defaultUrl = str_replace('/pages/web/index?url=https://', '', $defaultUrl);
        }

		$allUrls = array(
		    0 => array(
                "name"=>"商城页面",
                "list"=>array(
                    0 => array("name"=>"商城首页", "url"=>mobileUrl(null, null, $full), "url_wxapp"=>"/pages/index/index"),
                    1 => array("name"=>"分类导航", "url"=>mobileUrl("shop/category", null, $full), "url_wxapp"=>"/pages/shop/caregory/index"),
                    2 => array("name"=>"全部商品", "url"=>mobileUrl("goods", null, $full), "url_wxapp"=>"/pages/goods/index/index"),
                    3 => array("name"=>"公告页面", "url"=>mobileUrl("shop/notice", null, $full), "url_wxapp"=>"/pages/shop/notice/index/index"),
                    4 => array("name"=>"购物车", "url"=>mobileUrl("member/cart", null, $full), "url_wxapp"=>"/pages/member/cart/index")
                )
            ),
		    1 => array(
                "name"=>"商品属性",
                "list"=>array(
                    0 => array("name"=>"推荐商品", "url"=>mobileUrl("goods", array("isrecommand"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?isrecommand=1"),
                    1 => array("name"=>"新品上市", "url"=>mobileUrl("goods", array("isnew"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?isnew=1"),
                    2 => array("name"=>"热卖商品", "url"=>mobileUrl("goods", array("ishot"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?ishot=1"),
                    3 => array("name"=>"促销商品", "url"=>mobileUrl("goods", array("isdiscount"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?isdiscount=1"),
                    4 => array("name"=>"卖家包邮", "url"=>mobileUrl("goods", array("issendfree"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?issendfree=1"),
                    5 => array("name"=>"限时抢购", "url"=>mobileUrl("goods", array("istime"=>1), $full), "url_wxapp"=>"/pages/goods/index/index?istime=1")
                )
            ),
            2 => array(
                "name"=>"会员中心",
                "list"=>array(
                    0 => array("name"=>"会员中心", "url"=>mobileUrl("member", null, $full), "url_wxapp"=>"/pages/member/index/index"),
                    1 => array("name"=>"我的订单(全部)", "url"=>mobileUrl("order", null, $full), "url_wxapp"=>"/pages/order/index"),
                    2 => array("name"=>"待付款订单", "url"=>mobileUrl("order", array("status"=>0), $full), "url_wxapp"=>"/pages/order/index?status=0"),
                    3 => array("name"=>"待发货订单", "url"=>mobileUrl("order", array("status"=>1), $full), "url_wxapp"=>"/pages/order/index?status=1"),
                    4 => array("name"=>"待收货订单", "url"=>mobileUrl("order", array("status"=>2), $full), "url_wxapp"=>"/pages/order/index?status=2"),
                    5 => array("name"=>"退换货订单", "url"=>mobileUrl("order", array("status"=>4), $full), "url_wxapp"=>"/pages/order/index?status=4"),
                    6 => array("name"=>"已完成订单", "url"=>mobileUrl("order", array("status"=>3), $full), "url_wxapp"=>"/pages/order/index?status=3"),
                    7 => array("name"=>"我的收藏", "url"=>mobileUrl("member/favorite", array(), $full), "url_wxapp"=>"/pages/member/favorite/index"),
                    8 => array("name"=>"我的足迹", "url"=>mobileUrl("member/history", array(), $full), "url_wxapp"=>"/pages/member/history/index"),
                    9 => array("name"=>"会员充值", "url"=>mobileUrl("member/recharge", array(), $full), "url_wxapp"=>"/pages/member/recharge/index"),
                    10 => array("name"=>"余额明细", "url"=>mobileUrl("member/log", array(), $full), "url_wxapp"=>"/pages/member/log/index"),
                    11 => array("name"=>"余额提现", "url"=>mobileUrl("member/withdraw", array(), $full), "url_wxapp"=>"/pages/member/withdraw/index"),
                    12 => array("name"=>"我的资料", "url"=>mobileUrl("member/info", array(), $full), "url_wxapp"=>"/pages/member/info/index"),
                    13 => array("name"=>"积分排行", "url"=>mobileUrl("member/rank", array(), $full), "url_wxapp"=>""),
                    14 => array("name"=>"消费排行", "url"=>mobileUrl("member/rank/order_rank", array(), $full), "url_wxapp"=>""),
                    //15 => array("name"=>"消息提醒设置", "url"=>mobileUrl("member/notice", array(), $full), "url_wxapp"=>""),
                    16 => array("name"=>"收货地址管理", "url"=>mobileUrl("member/address", array(), $full), "url_wxapp"=>"/pages/member/address/index"),
                    18 => array("name"=>"我的全返", "url"=>mobileUrl("member/fullback", array(), $full), "url_wxapp"=>""),
                    19 => array("name"=>"记次时商品", "url"=>mobileUrl("verifygoods", array(), $full), "url_wxapp"=>""),
                )
            ),
        );

        //小程序选择链接商城页面添加链接
        if($platform){
            if(cv('creditshop') && p('creditshop')){
                $allUrls[0]['list'][] = array("name"=>"积分商城", "url"=>mobileUrl(null, null, $full), "url_wxapp"=>"/pages/creditshop/index");
            }
            if(cv('commission') && p('commission')){
                $allUrls[0]['list'][] = array("name"=>"分销中心", "url"=>mobileUrl('commission', null, $full), "url_wxapp"=>"/pages/transfer/commission/index");
            }

//            if($platform!='wxapp_tabbar' && $platform != '' ){
//                if(cv('bargain') && p('bargain')){
//                    $allUrls[0]['list'][] = array("name"=>"砍价首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/pages/bargain/index/index");
//                }
//            }
            $allUrls[2]['list'][]=array("name"=>"我的全返", "url"=>mobileUrl("member/fullback", array(), $full), "url_wxapp"=>"/commission/pages/return/index");
            $allUrls[2]['list'][]=array("name"=>"记次时商品", "url"=>mobileUrl("verifygoods", array(), $full), "url_wxapp"=>"/pages/verifygoods/index");

            //如果不是底部导航的话显示秒杀链接
//            if($platform!='wxapp_tabbar' && $platform != '' ){
//                if(cv('seckill') && p('seckill')){
//                    $allUrls[0]['list'][] = array("name"=>"秒杀首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/seckill/pages/index/index");
//                }
//            }

            if($platform!='wxapp_tabbar' && $platform != '' ){
                if(cv('dividend') && p('dividend')){
                    $allUrls[0]['list'][] = array("name"=>"团队分红", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/dividend/pages/index/index");
                }
            }

            if(cv('groups') && p('groups')){
                $allUrls[0]['list'][] = array("name"=>"拼团首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/pages/transfer/groups/index");
            }
            if(cv('groups') && p('seckill')){
                $allUrls[0]['list'][] = array("name"=>"秒杀首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/pages/transfer/seckill/index");
            }
            if(cv('groups') && p('bargain')){
                $allUrls[0]['list'][] = array("name"=>"砍价首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/pages/transfer/bargain/index");
            }
        }
//        if($platform!='wxapp_tabbar' && $platform != ''){
//            if(cv('groups') && p('groups')){
//                $allUrls[0]['list'][] = array("name"=>"拼团首页", "url"=>mobileUrl('', null, $full), "url_wxapp"=>"/pages/groups/index/index");
//            }
//        }
        //如果有周期购
        if(p('cycelbuy')){
            $allUrls[2]['list'][]=array("name"=>"周期购订单列表", "url"=>mobileUrl("cycelbuy/order/list", array(), $full), "url_wxapp"=>"/pages/order/cycle/order");
        }
        //如果有付费会员卡
        if(p('membercard')){
            $allUrls[2]['list'][]=array("name"=>"会员卡中心", "url"=>mobileUrl("membercard/index", array(), $full), "url_wxapp"=>"/pages/member/membercard/index");
        }
        if(p('dividend') && !$platform){
            $allUrls[0]['list'][]=array("name"=>"分红中心", "url"=>mobileUrl("dividend", array(), $full), "url_wxapp"=>"/pages/order/cycle/order");
        }
        if(p('exchange') && !$platform) {
            $allUrls[0]['list'][] =array("name" => "兑换中心", "url" => mobileUrl("exchange", array("codetype" => 1, "all" => 1), $full), "url_wxapp" => "");
        }
        if(p('abonus') && !$platform) {
            $allUrls[0]['list'][] =array("name" => "区域代理", "url" => mobileUrl("abonus", $full), "url_wxapp" => "");
        }
        if($platform){
            /*
             * 小程序自定义页面--咖啡
             * */
            $customs = pdo_fetchall("select id,`name` from ".tablename('ewei_shop_wxapp_page')." where uniacid = :uniacid and `type` = 20 and status = 1 ",array(':uniacid'=>$_W['uniacid']));
            if(!empty($customs)){
                $addUrl = array(
                    "name"=>"自定义页面",
                    "list"=>array()
                );
                $urllist = array();
                /*
                 * 将所有启用自定义页面添加到链接中--咖啡
                 * */

                foreach ($customs as $key=>$value){
                    if($type == 'topmenu'){
                        $urllist[$key] = array("name"=>$value['name'], "url"=>'', "url_wxapp"=>"/pages/custom/index?pageid=".$value['id']);
                        $diypage = pdo_fetch('SELECT * FROM ' .tablename('ewei_shop_wxapp_page'). ' WHERE id=:id AND uniacid = :uniacid',array(':id'=>$value['id'],':uniacid' => $_W['uniacid']));
                        $diypageData = json_decode(base64_decode($diypage['data']),true);
                        if(!empty($diypageData['items'])) {
                            foreach($diypageData['items'] as $dk => $dv){
                                if($dv['id'] == 'topmenu'){
                                    unset($urllist[$key]);
                                }
                            }
                        }
                    }else{
                        $urllist[$key] = array("name"=>$value['name'], "url"=>'', "url_wxapp"=>"/pages/custom/index?pageid=".$value['id']);
                    }
                }
                $addUrl['list'] = $urllist;
                array_push($allUrls,$addUrl);
            }

            unset($allUrls[2]['list'][13], $allUrls[2]['list'][14], $allUrls[2]['list'][15], $allUrls[2]['list'][18], $allUrls[2]['list'][19]);
            if($platform=='wxapp_tabbar'){
                // 处理底部菜单
                unset($allUrls[1], $allUrls[2]['list'][2], $allUrls[2]['list'][3], $allUrls[2]['list'][4], $allUrls[2]['list'][5], $allUrls[2]['list'][6]);
            }
        }

        // 分销中心
        if(cv('commission.agent.edit') && p('commission') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('commission'),
                "list" => array(
                    0 => array("name"=>"分销中心", "url"=>mobileUrl("commission", null, $full), "url_wxapp"=>"/pages/commission/index"),
                    1 => array("name"=>"成为分销商", "url"=>mobileUrl("commission/register", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"我的小店", "url"=>mobileUrl("commission/myshop", null, $full), "url_wxapp"=>""),
                    3 => array("name"=>"分销佣金/佣金提现", "url"=>mobileUrl("commission/withdraw", null, $full), "url_wxapp"=>""),
                    4 => array("name"=>"分销订单", "url"=>mobileUrl("commission/order", null, $full), "url_wxapp"=>""),
                    5 => array("name"=>"我的下线", "url"=>mobileUrl("commission/down", null, $full), "url_wxapp"=>""),
                    6 => array("name"=>"提现明细", "url"=>mobileUrl("commission/log", null, $full), "url_wxapp"=>""),
                    7 => array("name"=>"推广二维码", "url"=>mobileUrl("commission/qrcode", null, $full), "url_wxapp"=>""),
                    8 => array("name"=>"小店设置", "url"=>mobileUrl("commission/myshop/set", null, $full), "url_wxapp"=>""),
                    9 => array("name"=>"佣金排名", "url"=>mobileUrl("commission/rank", null, $full), "url_wxapp"=>""),
                    10 => array("name"=>"自选商品", "url"=>mobileUrl("commission/myshop/select", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 华仔定制文案分销
        if(cv('offic.system.edit') && p('offic') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('offic'),
                "list" => array(
                    0 => array("name"=>"选品", "url"=>mobileUrl("offic", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"发现", "url"=>mobileUrl("offic/find", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"我的分店", "url"=>mobileUrl("commission/branch", null, $full), "url_wxapp"=>""),
                    3 => array("name"=>"我的粉丝", "url"=>mobileUrl("commission/branch/fans", null, $full), "url_wxapp"=>""),
                    4 => array("name"=>"我的小店", "url"=>mobileUrl("offic/myshop", null, $full), "url_wxapp"=>""),
                )
            );
        }
        // 文章营销
        if(cv('article.edit') && p('article') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('article'),
                "list" => array(
                    0 => array("name"=>"文章列表页面", "url"=>mobileUrl("article/list", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 优惠券
            $allUrls[] = array(
                "name" => m('plugin')->getComName('coupon'),
                "list" => array(
                    0 => array("name"=>"领取优惠券", "url"=>mobileUrl("sale/coupon", null, $full), "url_wxapp"=>"/pages/sale/coupon/index/index"),
                    1 => array("name"=>"我的优惠券", "url"=>mobileUrl("sale/coupon/my", null, $full), "url_wxapp"=>"/pages/sale/coupon/my/index/index")
                )
            );
        // 拼团
        if(cv('groups.goods.edit') && p('groups') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('groups'),
                "list" => array(
                    0 => array("name"=>"拼团首页", "url"=>mobileUrl("groups", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"活动列表", "url"=>mobileUrl("groups/category", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"我的订单", "url"=>mobileUrl("groups/orders", null, $full), "url_wxapp"=>""),
                    3 => array("name"=>"我的团", "url"=>mobileUrl("groups/team", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 手机充值
        if(cv('mr.goods.edit') && p('mr') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('mr'),
                "list" => array(
                    0 => array("name"=>"充值页面", "url"=>mobileUrl("mr", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"充值记录", "url"=>mobileUrl("mr/order", null, $full), "url_wxapp"=>"")
                )
            );
        }

        // 人人社区
        if(cv('sns.adv.edit') && p('sns') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('sns'),
                "list" => array(
                    0 => array("name"=>"社区首页", "url"=>mobileUrl("sns", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"全部板块", "url"=>mobileUrl("sns/board/lists", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"我的社区", "url"=>mobileUrl("sns/user", null, $full), "url_wxapp"=>"")
                )
            );
        }

        // 积分签到
        if(cv('sign.rule.edit') && p('sign') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('sign'),
                "list" => array(
                    0 => array("name"=>"签到首页", "url"=>mobileUrl("sign", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"签到排行", "url"=>mobileUrl("sign/rank", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 帮助中心
        if(cv('qa.adv.edit') && p('qa') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('qa'),
                "list" => array(
                    0 => array("name"=>"帮助首页", "url"=>mobileUrl("qa", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"全部分类", "url"=>mobileUrl("qa/category", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"全部问题", "url"=>mobileUrl("qa/question", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 砍价
//        if(cv('bargain.react') && p('bargain') && !$platform){
//            $allUrls[] = array(
//                "name" => m('plugin')->getName('bargain'),
//                "list" => array(
//                    0 => array("name"=>"砍价首页", "url"=>mobileUrl("bargain", null, $full), "url_wxapp"=>"")
//                )
//            );
//        }
        // 任务中心
        if(cv('task.edit') && p('task') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('task'),
                "list" => array(
                    0 => array("name"=>"首页", "url"=>mobileUrl("task", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 积分商城
        if(cv('creditshop.goods.edit') && p('creditshop') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('creditshop'),
                "list" => array(
                    0 => array("name"=>"商城首页", "url"=>mobileUrl("creditshop", null, $full), "url_wxapp"=>""),
                    1 => array("name"=>"全部商品", "url"=>mobileUrl("creditshop/lists", null, $full), "url_wxapp"=>""),
                    2 => array("name"=>"我的", "url"=>mobileUrl("creditshop/log", null, $full), "url_wxapp"=>""),
                    3 => array("name"=>"参与记录", "url"=>mobileUrl("creditshop/creditlog", null, $full), "url_wxapp"=>""),
                )
            );
        }
        // 整点秒杀
        if(cv('seckill.task.edit') && p('seckill') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('seckill'),
                "list" => array(
                    0 => array("name"=>"秒杀首页", "url"=>mobileUrl("seckill", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // O2O
        if(cv('newstore.temp.edit') && p('newstore') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('newstore'),
                "list" => array(
                    0 => array("name"=>"门店列表", "url"=>mobileUrl("newstore/stores", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 全民股东
        if(cv('globonus.partner.edit') && p('globonus') && !$platform){
            $allUrls[] = array(
                "name" => m('plugin')->getName('globonus'),
                "list" => array(
                    0 => array("name"=>"股东首页", "url"=>mobileUrl("globonus", null, $full), "url_wxapp"=>"")
                )
            );
        }
        // 万能客服
        $key = pdo_fetch("SELECT id FROM ".tablename('ewei_shop_open_plugin')." WHERE `status` =1 and plugin = :plugin", array(':plugin' =>'open_messikefu'));

        if(cv('open_messikefu') && p('open_messikefu') && !$platform && $key){
            $allUrls[] = array(
                "name" => m('plugin')->getName('open_messikefu'),
                "list" => array(
                    0 => array("name"=>"万能客服", "url"=>mobileUrl("open_messikefu", null, $full), "url_wxapp"=>"")
                )
            );
        }

        // 这个地方是链接瓜分券活动链接列表,所以不能直接放链接到商城链接选择器
        if(cv('friendcoupon.edit') && p('friendcoupon') && !$platform) {
            $activities = p('friendcoupon')->getActivityList();
        }
        
        if($type == 'levellink'){
            unset($allUrls);
            $allUrls = array();
            if($platform){

                /*
                 * 小程序自定义页面--咖啡
                 * */
                $customs = pdo_fetchall("select id,`name` from ".tablename('ewei_shop_wxapp_page')." where uniacid = :uniacid and `type` = 20 and status = 1 ",array(':uniacid'=>$_W['uniacid']));
                if(!empty($customs) && $platform!='wxapp_tabbar'){
                    $addUrl = array(
                        "name"=>"自定义页面",
                        "list"=>array()
                    );
                    $urllist = array();
                    /*
                     * 将所有启用自定义页面添加到链接中--咖啡
                     * */

                    foreach ($customs as $key=>$value){
                        if($type == 'levellink'){
                            $urllist[$key] = array("name"=>$value['name'], "url"=>'', "url_wxapp"=>"/pages/custom/index?pageid=".$value['id']);
                            $diypage = pdo_fetch('SELECT * FROM ' .tablename('ewei_shop_wxapp_page'). ' WHERE id=:id AND uniacid = :uniacid',array(':id'=>$value['id'],':uniacid' => $_W['uniacid']));
                            $diypageData = json_decode(base64_decode($diypage['data']),true);
                            if(!empty($diypageData['items'])) {
                                foreach($diypageData['items'] as $dk => $dv){
                                    if($dv['id'] == 'topmenu' || $dv['id'] == 'tabbar'){
                                        unset($urllist[$key]);
                                    }
                                }
                            }
                        }else{
                            $urllist[$key] = array("name"=>$value['name'], "url"=>'', "url_wxapp"=>"/pages/custom/index?pageid=".$value['id']);
                        }
                    }
                    $addUrl['list'] = $urllist;
                    array_push($allUrls,$addUrl);
                }

            }
        }

        if ($platform == 'pc') {
            $allUrls = array();
            $allUrls = p('pc')->getLinkList();

        }
        
		include $this->template();
	}

	/*
	 * 页面搜索
	 * */
	public function query(){
		global $_W, $_GPC;

		$type = trim($_GPC['type']);
		$kw = trim($_GPC['kw']);
		$full = intval($_GPC['full']);
		$platform = trim($_GPC['platform']);

		if(!empty($kw) && !empty($type)){

			if($type=='good'){
				$list = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb,sales,unit,minprice FROM " . tablename('ewei_shop_goods') . " WHERE uniacid= :uniacid and status=:status and deleted=0 AND title LIKE :title ", array(':title' => "%{$kw}%", ':uniacid' => $_W['uniacid'], ':status' => '1'));
				$list = set_medias($list, 'thumb');
			}else if($type == 'goods_data_diy'){
			    if($kw == 'all'){
                    $list = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb,sales,unit,minprice FROM " . tablename('ewei_shop_goods') . " WHERE uniacid= :uniacid and status=:status and deleted=0 ", array( ':uniacid' => $_W['uniacid'], ':status' => '1'));
                    $list = set_medias($list, 'thumb');
                }else{
                    $list = pdo_fetchall("SELECT id,title,productprice,marketprice,thumb,sales,unit,minprice FROM " . tablename('ewei_shop_goods') . " WHERE uniacid= :uniacid and status=:status and deleted=0 AND title LIKE :title ", array(':title' => "%{$kw}%", ':uniacid' => $_W['uniacid'], ':status' => '1'));
                    $list = set_medias($list, 'thumb');
                }
            }
			elseif($type=='article'){
				$list = pdo_fetchall("select id,article_title from " . tablename('ewei_shop_article') . ' where article_title LIKE :title and article_state=1 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));
			}
			elseif($type=='coupon'){
				$list = pdo_fetchall("select id,couponname,coupontype from " . tablename('ewei_shop_coupon') . ' where couponname LIKE :title and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));
			}
			elseif($type=='groups'){
				$list = pdo_fetchall("select id,title from " . tablename('ewei_shop_groups_goods') . ' where title LIKE :title and status=1 and deleted=0 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));
			}
            elseif($type=='sns'){
                $list_board = pdo_fetchall("select id,title from " . tablename('ewei_shop_sns_board') . ' where title LIKE :title and status=1 and enabled=0 and uniacid=:uniacid order by id desc ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));
                $list_post = pdo_fetchall("select id,title from " . tablename('ewei_shop_sns_post') . ' where title LIKE :title and checked=1 and deleted=0 and uniacid=:uniacid order by id desc ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));

                $list = array();
                if(!empty($list_board) && is_array($list_board)){
                    foreach ($list_board as &$board){
                        $board['type'] = 0;
                        $board['url'] = mobileUrl('sns/board', array('id'=>$board['id'], 'page'=>1), $full);
                    }
                    unset($board);
                    $list = array_merge($list, $list_board);
                }
                if(!empty($list_post) && is_array($list_post)){
                    foreach ($list_post as &$post){
                        $post['type'] = 1;
                        $post['url'] = mobileUrl('sns/post', array('id'=>$post['id']), $full);
                    }
                    unset($post);
                    $list = array_merge($list, $list_post);
                }
            }
            elseif($type=='creditshop'){
                $list = pdo_fetchall("select id, thumb, title, price, credit, money from " . tablename('ewei_shop_creditshop_goods') . ' where title LIKE :title and status=1 and deleted=0 and uniacid=:uniacid ', array(':uniacid' => $_W['uniacid'], ':title' => "%{$kw}%"));
            }
		}

		include $this->template('util/selecturl_tpl');
	}

}
