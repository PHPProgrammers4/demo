<?php
/*
 * 人人商城
 *
 * 青岛易联互动网络科技有限公司
 * http://www.we7shop.cn
 * TEL: 4000097827/18661772381/15865546761
 */
error_reporting(0);
require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/ewei_shopv2/defines.php';
require '../../../../../addons/ewei_shopv2/core/inc/functions.php';
global $_W, $_GPC;


ignore_user_abort(); //忽略关闭浏览器
set_time_limit(0); //永远执行

$sets = pdo_fetchall('select uniacid,refund from ' . tablename('ewei_shop_groups_set'));
foreach ($sets as $key => $value) {
    global $_W, $_GPC;
    $_W['uniacid'] = $value['uniacid'];
    if (empty($_W['uniacid'])) {
        continue;
    }
    $params= array(':uniacid'=> $_W['uniacid']);
    //检测未付款订单,24小时未付款订单自动取消
    $times = 24 * 60 * 60;
    $sql= "SELECT id,status FROM".tablename('ewei_shop_groups_order')." where uniacid = :uniacid and status = 0 and createtime + {$times} <= ".time()." ";
    $orders = pdo_fetchall($sql, $params);
    foreach ($orders as $k => $val) {
        if(!empty($val) && $val['status']==0){
            pdo_query("update " . tablename('ewei_shop_groups_order') . ' set status=-1,canceltime=' . time() . ' where id=' . $val['id']);
        }
    }
    //检测拼团中的订单是否过期
    $sql1 = "SELECT * FROM".tablename('ewei_shop_groups_order')." where uniacid = :uniacid and heads = 1 and status = 1 and success = 0 ";
    $allteam = pdo_fetchall($sql1, $params);
    foreach($allteam as $k => $val){
        $total = pdo_fetchcolumn('select count(1) from ' . tablename('ewei_shop_groups_order') . "  where uniacid = :uniacid and teamid = :teamid and heads = :heads and status = :status and success = :success and is_team = 1  ",
            array(':uniacid'=>$_W['uniacid'],':heads'=>1,':teamid'=>$val['teamid'],':status'=>1,':success'=>0));

        //拼团人数
        $groups_num = $val['groupnum'];
        //如果是阶梯团的话 人数改成阶梯团人数 @author 青椒  @date 5/19/2018
        if( $val['is_ladder'] == 1 ){
            $ladder = pdo_get( 'ewei_shop_groups_ladder' , array( 'id' => $val['ladder_id'] ) );
            $groups_num = $ladder['ladder_num'];
        }

        if($groups_num == $total){
            //是否满足拼团人数
            pdo_update('ewei_shop_groups_order', array('success'=>1), array('teamid' => $val['teamid']));
            //拼团成功发货通知
            p('groups')->sendTeamMessage($val['id']);
        }else{
            //检测拼团结束时间
            $hours = $val['endtime'];
            $time = time();
            $date = date('Y-m-d H:i:s',$val['starttime']); //团长开团时间
            $endtime = date('Y-m-d H:i:s',strtotime(" $date + $hours hour"));

            $date1 = date('Y-m-d H:i:s',$time); //当前时间
            $lasttime2 = strtotime($endtime)-strtotime($date1);//剩余时间（秒数）

            if($lasttime2 < 0){
                pdo_update('ewei_shop_groups_order', array('success'=>-1,'canceltime'=>$time), array('teamid' => $val['teamid']));
                //拼团失败发货通知
                p('groups')->sendTeamMessage($val['id']);
            }
        }
    }
}




