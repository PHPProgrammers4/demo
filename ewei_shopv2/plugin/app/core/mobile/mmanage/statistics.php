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

require EWEI_SHOPV2_PLUGIN . 'app/core/page_auth_mobile.php';

class Statistics_EweiShopV2Page extends AppMobileAuthPage {

    public function main(){

        $notice = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_system_copyright_notice') . " WHERE status=1 ORDER BY displayorder ASC,createtime DESC LIMIT 10" );

        if(!empty($notice)){
            foreach ($notice as &$item){
                $item['createtime'] = date('Y-m-d H:i:s', $item['createtime']);
            }
        }

        return app_json(array(
            'list'=>$notice
        ));
    }
    /*
     * 销售统计
     * 交易额、交易量
     * */
    public function sale(){
        global $_W, $_GPC;
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

        //年份
        $years = array();
        $current_year = date('Y');
        $year = empty($_GPC['year']) ? $current_year : $_GPC['year'];
        for ($i = $current_year - 10; $i <= $current_year; $i++) {
            $years[] = array('data' => $i, 'selected' => ($i == $year));
        }
        //月份
        $months = array();
        $current_month = date('m');
        $month = $_GPC['month'];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = array('data' => $i, 'selected' => ($i == $month));
        }


        $day = intval($_GPC['day']);


        //查询类型
        $type = intval($_GPC['type']);

        $list = array();
        $totalcount = 0;  //总数
        $maxcount = 0;  //最高
        $maxcount_date = ''; //最高的日期
        $maxdate = '';    //最高的时间
        $countfield = empty($type) ? 'sum(price)' : 'count(*)';
        $typename = empty($type) ? '交易额' : '交易量';
        $dataname = empty($month) ? '月份' : '日期';

        if (!empty($year) && !empty($month) && !empty($day)) {


            for ($hour = 0; $hour < 24; $hour++) {
                $nexthour = $hour+1;
                $dr = array(
                    'data' => $hour.'点 - '.$nexthour."点",
                    'count' => pdo_fetchcolumn("SELECT ifnull({$countfield},0) as cnt FROM " . tablename('ewei_shop_order') . " WHERE uniacid=:uniacid and status>=1 and createtime >=:starttime and createtime <=:endtime", array(
                        ':uniacid' => $_W['uniacid'],
                        ':starttime' => strtotime("{$year}-{$month}-{$day} {$hour}:00:00"),
                        ':endtime' => strtotime("{$year}-{$month}-{$day} {$hour}:59:59")
                    ))
                );

                $totalcount+=$dr['count'];
                if ($dr['count'] > $maxcount) {
                    $maxcount = $dr['count'];
                    $maxcount_date = "{$year}年{$month}月{$day}日 {$hour}点 - {$nexthour}点";
                }

                $list[] = $dr;
            }

        }
        else if (!empty($year) && !empty($month)) {
            $lastday = get_last_day($year, $month);
            for ($d = 1; $d <= $lastday; $d++) {
                $dr = array(
                    'data' => $d,
                    'count' => pdo_fetchcolumn("SELECT ifnull({$countfield},0) as cnt FROM " . tablename('ewei_shop_order') . " WHERE uniacid=:uniacid and status>=1 and isparent=0 and createtime >=:starttime and createtime <=:endtime", array(
                        ':uniacid' => $_W['uniacid'],
                        ':starttime' => strtotime("{$year}-{$month}-{$d} 00:00:00"),
                        ':endtime' => strtotime("{$year}-{$month}-{$d} 23:59:59")
                    ))
                );

                $totalcount+=$dr['count'];
                if ($dr['count'] > $maxcount) {
                    $maxcount = $dr['count'];
                    $maxcount_date = "{$year}年{$month}月{$d}日";
                }

                $list[] = $dr;
            }
        } else if (!empty($year)) {


            foreach ($months as $k=>$m) {
                $lastday = get_last_day($year, $k+1);
                $dr = array(
                    'data' => $m['data'],
                    'count' => pdo_fetchcolumn("SELECT ifnull({$countfield},0) as cnt FROM " . tablename('ewei_shop_order') . " WHERE uniacid=:uniacid and status>=1 and createtime >=:starttime and createtime <=:endtime", array(
                            ':uniacid' => $_W['uniacid'],
                            ':starttime' => strtotime("{$year}-{$m['data']}-01 00:00:00"),
                            ':endtime' => strtotime("{$year}-{$m['data']}-{$lastday} 23:59:59")
                        )
                    )
                );
                $totalcount+=$dr['count'];
                if ($dr['count'] > $maxcount) {
                    $maxcount = $dr['count'];
                    $maxcount_date = "{$year}年{$m['data']}月";
                }
                $list[] = $dr;
            }
        }
        foreach ($list as $key => &$row) {
            $list[$key]['percent'] = number_format($row['count'] / (empty($totalcount) ? 1 : $totalcount) * 100, 2);
        }
        unset($row);

        return app_json(array(
            'list'=>$list,
            'totalcount'=>$totalcount,
            'maxcount'=>$maxcount,
            'type'=>$type,
            'maxcount_date'=>$maxcount_date,
        ));
    }
    /*
     * 获取年/月，天数
     * */
    public function get_day(){
        global $_W, $_GPC;
        $year = intval($_GPC['year']);
        $month = intval($_GPC['month']);
        $day = get_last_day($year, $month);

        return app_json(array( 'day' => $day));
    }
    /*
     * 销售明细
     * */
    public function goods(){
        global $_W, $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $condition = ' and og.uniacid=:uniacid and o.status>=1';
        $params = array(':uniacid' => $_W['uniacid']);
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime = time();
        }

        if (!empty($_GPC['datestart']) && !empty($_GPC['dateend'])) {
            $starttime = intval($_GPC['datestart']);
            $endtime = intval($_GPC['dateend']);

            if (!empty($starttime)) {
                $condition .= " AND o.createtime >= :starttime";
                $params[':starttime'] = $starttime;
            }

            if (!empty($endtime)) {
                $condition .= " AND o.createtime <= :endtime ";
                $params[':endtime'] = $endtime;
            }
        }

        if (!empty($_GPC['title'])) {
            $_GPC['title'] = trim($_GPC['title']);
            $condition.=" and g.title like :title";
            $params[':title'] = "%{$_GPC['title']}%";
        }
        $orderby = !isset($_GPC['orderby']) ? 'og.price' : ( empty($_GPC['orderby']) ? 'og.price' : 'og.total');

        $sql = "select og.price,og.total,o.createtime,o.ordersn,g.title,g.thumb,g.goodssn,op.goodssn as optiongoodssn,op.title as optiontitle from " . tablename('ewei_shop_order_goods') . ' og '
            . " left join " . tablename('ewei_shop_order') . " o on o.id = og.orderid "
            . " left join " . tablename('ewei_shop_goods') . " g on g.id = og.goodsid "
            . " left join " . tablename('ewei_shop_goods_option') . " op on op.id = og.optionid "
            . " where 1 {$condition} order by {$orderby} desc ";
        if (empty($_GPC['export'])) {
            $sql.="LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        }
        $list = pdo_fetchall($sql, $params);
        foreach ($list as &$row) {
            $row['thumb'] = tomedia($row['thumb']);
            $row['price'] = price_format($row['price']);
            $row['createtime'] = date("Y-m-d H:i:s",$row['createtime']);
            if (!empty($row['optiongoodssn'])) {
                $row['goodssn'] = $row['optiongoodssn'];
            }
        }
        unset($row);
        $total = pdo_fetchcolumn("select  count(*) from " . tablename('ewei_shop_order_goods') . ' og '
            . " left join " . tablename('ewei_shop_order') . " o on o.id = og.orderid "
            . " left join " . tablename('ewei_shop_goods') . " g on g.id = og.goodsid "
            . " where 1 {$condition}", $params);
        /*app_json(array(
            'list'=>$list
        ));*/
        return app_json(array( 'list' => $list, 'pagesize' => $psize, 'total' => $total ,'page'=>$pindex));
    }

    /*public function detail() {
        global $_GPC;

        $id = intval($_GPC['id']);
        if(empty($id)){
            return app_error(AppError::$ParamsError, '参数错误');
        }

        $item = pdo_fetch("SELECT * FROM " . tablename('ewei_shop_system_copyright_notice') . " WHERE id=:id AND status=1 LIMIT 1", array('id'=>$id));
        if(empty($item)){
            return app_error(AppError::$ParamsError, '公告不存在');
        }
        $item['createtime'] = !empty($item['createtime'])? date('Y-m-d H:i:s', $item['createtime']): 0;

        app_json(array(
            'detail'=>$item
        ));
    }*/

}
