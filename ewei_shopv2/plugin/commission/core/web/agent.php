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

class Agent_EweiShopV2Page extends PluginWebPage {

    function main() {
        global $_W, $_GPC;

        $agentlevels = $this->model->getLevels(true, true);
        //获取分销商等级
        $level = $this->set['level'];
        //級別
        $pindex = max(1, intval($_GPC['page'])); 
        //分页
        $psize = 20;
        //尺寸大小
        $params = array();
        //参数定义为空
        $condition = '';
            
        //条件定义为空
        $searchfield = strtolower(trim($_GPC['searchfield']));
        //搜索字段 取整小写
        $keyword = trim($_GPC['keyword']);
        //获取关键字 去空格
        if (!empty($searchfield) && !empty($keyword)) {
            //判断传递过来的搜索字段是否是分销商
            if ($searchfield == 'member') {
                
                $condition.=' and ( dm.realname like :keyword or dm.nickname like :keyword or dm.mobile like :keyword)';
                $params[':keyword'] = "%{$keyword}%";
                //判断传递过来的搜索字段是否是推荐人
            } else if ($searchfield == 'parent') {

                if ($keyword == '总店') {
                    $condition.=' and dm.agentid=0';
                } else {

                    $condition.=' and ( p.mobile like :keyword or p.nickname like :keyword or p.realname like :keyword)';
                    $params[':keyword'] = "%{$keyword}%";
                }
            }
        }
            //根据传递的 关注 字段进行判断
        if ($_GPC['followed'] != '') {
            if ($_GPC['followed'] == 2) {
                $condition.=' and f.follow=0 and f.unfollowtime<>0';
            } else {
                $condition.=' and f.follow=' . intval($_GPC['followed']). ' and f.unfollowtime=0 ';
            }
        }
        //根据成为分销商的时间进行判断
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime = time();
        }

        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']);
            $condition .= " AND dm.agenttime >= :starttime AND dm.agenttime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime'] = $endtime;
        }
        //根据分销商等级判断
        if ($_GPC['agentlevel'] != '') {
            $condition.=' and dm.agentlevel=' . intval($_GPC['agentlevel']);
        }
        //根据是否是黑名单判断
        if ($_GPC['isagentblack'] != '') {
            $condition.=' and dm.agentblack=' . intval($_GPC['isagentblack']);
        }
        //判断用户的状态0/1 审核未审核
        if ($_GPC['status'] != '') {
            $condition.=' and dm.status=' . intval($_GPC['status']);
        }
        //设置黑名单
        if ($_GPC['agentblack'] != '') {
            $condition.=' and dm.agentblack=' . intval($_GPC['agentblack']);
        }
        //根据拼接好的条件进行查询                                          
        //用户表的             昵称          头像 分销等级表会员等级名称 分销商等级
        $sql = "select dm.*,dm.nickname,dm.avatar,l.levelname,p.agentlevel as parentagentlevel, p.mobile as parentmobile,p.id as pid,p.nickname as parentname,p.avatar as parentavatar,f.follow as followed,p.realname as parentrealname, f.unfollowtime from " . tablename('ewei_shop_member') . " dm "
                . " left join " . tablename('ewei_shop_member') . " p on p.id = dm.agentid " //根据用户表的ID和代理商ID左关联
                . " left join " . tablename('ewei_shop_commission_level') . " l on l.id = dm.agentlevel"//分销等级表的ID和用户表分销等级
                . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid and f.uniacid={$_W['uniacid']}"
                . " where dm.uniacid = " . $_W['uniacid'] . " and dm.isagent =1  {$condition} ORDER BY dm.status ASC ,dm.createtime desc";
            //判断是否点击了导出    
        if (empty($_GPC['export'])) {
            $sql.=" limit " . ($pindex - 1) * $psize . ',' . $psize;
        }
        //根据关键字查询
        $list = pdo_fetchall($sql, $params);
         //获取数据总数   
        $total = pdo_fetchcolumn("select count(dm.id) from" . tablename('ewei_shop_member') . " dm  "
                . " left join " . tablename('ewei_shop_member') . " p on p.id = dm.agentid "
                . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid"
                . " where dm.uniacid =" . $_W['uniacid'] . " and dm.isagent =1 {$condition}", $params);
            //引用$row
        foreach ($list as &$row) {
            //分销商信息             (返回分销商信息)
            $info = $this->model->getInfo($row['openid'], array('total', 'pay'));
            $row['levelcount'] = $info['agentcount'];
            if ($level >= 1) {
                $row['level1'] = $info['level1'];
            }
            if ($level >= 2) {
                $row['level2'] = $info['level2'];
            }
            if ($level >= 3) {
                $row['level3'] = $info['level3'];
            }
            //获取积分余额
            $row['credit1'] = m('member')->getCredit($row['openid'], 'credit1'); //积分
            $row['credit2'] = m('member')->getCredit($row['openid'], 'credit2');//余额
            $row['commission_total'] = $info['commission_total']; //预计佣金总额
            $row['commission_pay'] = $info['commission_pay'];       //佣金支付金额
            $row['followed'] = m('user')->followed($row['openid']); //是否关注
            //                               用户表单字段                         //用户表单数据
            if (p('diyform')  && !empty($row['diymemberfields']) && !empty($row['diymemberdata'])) {
                $diyformdata_array = p('diyform')->getDatas(iunserializer($row['diymemberfields']), iunserializer($row['diymemberdata']));
                $diyformdata = "";
                foreach ($diyformdata_array as $da) {
                    $diyformdata.=$da['name'] . ": " . $da['value'] . "\r\n";
                }
                $row['member_diyformdata'] = $diyformdata;
            }
                                            //分销商表单字段                           //分销商表单数据
            if (p('diyform')  && !empty($row['diycommissionfields']) && !empty($row['diycommissiondata'])) {
                $diyformdata_array = p('diyform')->getDatas(iunserializer($row['diycommissionfields']), iunserializer($row['diycommissiondata']));
                $diyformdata = "";
                foreach ($diyformdata_array as $da) {
                    $diyformdata.=$da['name'] . ": " . $da['value'] . "\r\n";
                }
                $row['agent_diyformdata'] = $diyformdata;
            }


            //处理分销等级名称 by lgt
            if (!empty($agentlevels) || $_GPC['export'] == '1') {
                foreach ($agentlevels as $levels) {
                    if ($row['agentlevel'] == $levels['id']) {
                        $row['levelname'] = $levels['levelname'];
                    }
                    if ($row['parentagentlevel'] == $levels['id']) {
                        $row['parentlevelname'] = $levels['levelname'];
                    }
                }
            }
        }
        unset($row);
        //导出Excel
        if ($_GPC['export'] == '1') {

            ca('commission.agent.export');
            plog('commission.agent.export', '导出分销商数据');
            foreach ($list as &$row) {
                $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                $row['agenttime'] = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['agenttime']);
                $row['groupname'] = empty($row['groupname']) ? '无分组' : $row['groupname'];
                $row['levelname'] = empty($row['levelname']) ? '普通等级' : $row['levelname'];
                $row['parentname'] = empty($row['pid']) ? '总店' : "[" . $row['agentid'] . "]" . $row['parentname'];
                $row['statusstr'] = empty($row['status']) ? '未通过' : "通过";
                $row['followstr'] = empty($row['followed']) ? '' : "已关注";
                $row['realname'] = str_replace('=', "", $row['realname']);
                $row['nickname'] = str_replace('=', "", $row['nickname']);

            }

            unset($row);

            $columns = array(
                array('title' => 'ID', 'field' => 'id', 'width' => 12),
                array('title' => '昵称', 'field' => 'nickname', 'width' => 12),
                array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                array('title' => '微信号', 'field' => 'weixin', 'width' => 12),
                array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                array('title' => '推荐人', 'field' => 'parentname', 'width' => 12),
                array('title' => '分销商等级', 'field' => 'levelname', 'width' => 12),
                array('title' => '点击数', 'field' => 'clickcount', 'width' => 12),
                array('title' => '下线分销商总数', 'field' => 'levelcount', 'width' => 12),
                array('title' => '一级下线分销商数', 'field' => 'level1', 'width' => 12),
                array('title' => '二级下线分销商数', 'field' => 'level2', 'width' => 12),
                array('title' => '三级下线分销商数', 'field' => 'level3', 'width' => 12),
                array('title' => '累计佣金', 'field' => 'commission_total', 'width' => 12),
                array('title' => '打款佣金', 'field' => 'commission_pay', 'width' => 12),
                array('title' => '注册时间', 'field' => 'createtime', 'width' => 12),
                array('title' => '成为分销商时间', 'field' => 'agenttime', 'width' => 12),
                array('title' => '审核状态', 'field' => 'statusstr', 'width' => 12),
                array('title' => '是否关注', 'field' => 'followstr', 'width' => 12),
            );

            if (p('diyform')) {
                $columns[] = array('title' => '分销商会员自定义信息', 'field' => 'member_diyformdata', 'width' => 36);
                $columns[] = array('title' => '分销商申请自定义信息', 'field' => 'agent_diyformdata', 'width' => 36);
            }

            m('excel')->export($list, array(
                "title" => "分销商数据-" . date('Y-m-d-H-i', time()),
                "columns" => $columns
            ));
        }
        $pager = pagination2($total, $pindex, $psize);
        load()->func('tpl');
        include $this->template();
    }



    //注销分销商的方法
    function delete() {

        global $_W, $_GPC;


        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }

        $members = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_member') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);
        foreach ($members as $member) {
            pdo_update('ewei_shop_member', array('isagent' => 0, 'status' => 0), array('id' => $member['id']));
            //   根据传递的id去修改用户表中  取消用户经销商 和审核状态值
            plog('commission.agent.delete', "取消分销商资格 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
        }
        show_json(1, array('url' => referer()));
    }
    //点击用户分销数量跳转的方法
    function user() {

        global $_W, $_GPC;

        // 全部会员等级                获取所有分销商等级
        $agentlevels = $this->model->getLevels(true, true);

        $level_map = array(
            1 => '一级',
            2 => '二级',
            3 => '三级'
        );
        //等级名称
        $level_name = $level_map[$_GPC['level']];


        //下级代理
        $level = intval($_GPC['level']);
        $agentid = intval($_GPC['id']);
        //获取分销商所有信息
        $member = $this->model->getInfo($agentid);
        //获取会员信息
        $parentInfo = m('member')->getMember($member['agentid']);

        //分销商总数
        $total = $member['agentcount'];
        //等级
        $level1 = $member['level1'];
        $level2 = $member['level2'];
        $level3 = $member['level3'];
//        $level11 = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . ' where isagent=0 and agentid=:agentid and uniacid=:uniacid limit 1', array(':agentid' => $agentid, ':uniacid' => $_W['uniacid']));
        $downcount = 0;
        //下线人数默认0
        //
        //下级总人数（非分销商）
        //
        $downs1 = pdo_fetchall('select id from ' . tablename('ewei_shop_member') . ' where agentid=:agentid and uniacid=:uniacid and isagent=0', array(':agentid' => $member['id'], ':uniacid' => $_W['uniacid']), 'id');


        $downcount += count($downs1);

        if (!empty($downs1)) {
            $downs2 = pdo_fetchall('select id from ' . tablename('ewei_shop_member') . ' where agentid in( ' . implode(',', array_keys($downs1)) . ') and uniacid=:uniacid and isagent=0 ', array(':uniacid' => $_W['uniacid']), 'id');
            $downcount += count($downs2);
            if (!empty($downs2)) {
                $downs3 = pdo_fetchall('select id from ' . tablename('ewei_shop_member') . ' where agentid in( ' . implode(',', array_keys($downs2)) . ') and uniacid=:uniacidand isagent=0 ', array(':uniacid' => $_W['uniacid']), 'id');
                $downcount += count($downs3);
            }
        }
        // 所有分销商
        $child_num = pdo_fetchcolumn("select count(1) from " . tablename('ewei_shop_commission_relation') . " where pid = :pid", array(":pid" => $agentid));
        
        $level11 = $downcount;
        $newlevel = pdo_fetch('select * from ' . tablename('ewei_shop_commission_level') . " where uniacid=:uniacid  and {$downcount} >= downcount and downcount>0  order by downcount desc limit 1", array(':uniacid' => $_W['uniacid']));
        $condition = '';
        $params = array();
        $hasagent = false;
        if (empty($level) && !empty($this->set['level'])) {
            $condition = " and ( dm.agentid={$member['id']}";
            if ($level1 > 0 && $this->set['level']>1) {
                $condition.= " or  dm.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")";
            }
            if ($level2 > 0 && $this->set['level']>2) {
                $condition.= " or  dm.agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")";
            }
            $condition.=' )';
            $hasagent = true;
        } else if ($level == 1 && $this->set['level']>0) {
            if ($level1 > 0 || !empty($level11)) {
                $condition = " and dm.agentid={$member['id']}";
                $hasagent = true;
            }

        } else if ($level == 2 && $this->set['level']>1) {
            if ($level2 > 0) {
                $condition = " and dm.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")";
                $hasagent = true;
            }
        } else if ($level == 3 && $this->set['level']>2) {
            if ($level3 > 0) {
                $condition = " and dm.agentid in( " . implode(',', array_keys($member['level2_agentids'])) . ")";
                $hasagent = true;
            }
        }
        if (!empty($_GPC['mid'])) {
            $condition.=' and dm.id=:mid';
            $params[':mid'] = intval($_GPC['mid']);
        }
        $searchfield = strtolower(trim($_GPC['searchfield']));
        $keyword = trim($_GPC['keyword']);
        if(!empty($keyword)){
            $condition.=' and ( dm.realname like :keyword or dm.nickname like :keyword or dm.mobile like :keyword)';
            $params[':keyword'] = "%{$keyword}%";
        }
       /* if (!empty($searchfield) && !empty($keyword)) {

            if ($searchfield == 'member') {

                $condition.=' and ( dm.realname like :keyword or dm.nickname like :keyword or dm.mobile like :keyword)';
                $params[':keyword'] = "%{$keyword}%";
            } else if ($searchfield == 'parent') {

                if ($keyword == '总店') {
                    $condition.=' and dm.agentid=0';
                } else {

                    $condition.=' and ( p.mobile like :keyword or p.nickname like :keyword or p.realname like :keyword)';
                    $params[':keyword'] = "%{$keyword}%";
                }
            }
        }*/
        if ($_GPC['isagent'] != '') {
            $condition.=' and dm.isagent=' . intval($_GPC['isagent']);
        }

        if ($_GPC['agentlevel'] != '') {
            $condition.=' and dm.agentlevel=' . intval($_GPC['agentlevel']);
        }

        if ($_GPC['status'] != '') {
            $condition.=' and dm.status=' . intval($_GPC['status']);
        }
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime = time();
        }


        if (!empty($_GPC['time']['start']) && !empty($_GPC['time']['end'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime = strtotime($_GPC['time']['end']);
            $condition .= " AND dm.agenttime >= :starttime AND dm.agenttime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime'] = $endtime;
        }
        if ($_GPC['followed'] != '') {
            if ($_GPC['followed'] == 2) {
                $condition.=' and f.follow=0 and dm.uid<>0';
            } else {
                $condition.=' and f.follow=' . intval($_GPC['followed']);
            }
        }
        if ($_GPC['isagentblack'] != '') {
            $condition.=' and dm.agentblack=' . intval($_GPC['isagentblack']);
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = array();


        if ($hasagent) {

            $total = pdo_fetchcolumn("select count(dm.id) from" . tablename('ewei_shop_member') . " dm "
                    . " left join " . tablename('ewei_shop_member') . " p on p.id = dm.agentid "
                    . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid"
                    . " where dm.uniacid =" . $_W['uniacid'] . "  {$condition}", $params);
            $sql = "select dm.*,p.nickname as parentname,p.avatar as parentavatar,l.levelname as levelname  from " . tablename('ewei_shop_member') . " dm "
                . " left join " . tablename('ewei_shop_member') . " p on p.id = dm.agentid "
                . " left join " . tablename('ewei_shop_commission_level') . " l on l.id = dm.agentlevel"
                . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid  and f.uniacid={$_W['uniacid']}"
                . " where dm.uniacid = " . $_W['uniacid'] . "  {$condition}   ORDER BY dm.agenttime desc";

            if (empty($_GPC['export'])) {
                $sql .= " limit " . ($pindex - 1) * $psize . ',' . $psize;
            }
            $list = pdo_fetchall($sql, $params);
            $pager = pagination($total, $pindex, $psize);
            foreach ($list as &$row) {
                $info = $this->model->getInfo($row['openid'], array('total', 'pay'));
                $row['levelcount'] = $info['agentcount'];
                if ($this->set['level'] >= 1) {
                    $row['level1'] = $info['level1'];
                }
                if ($this->set['level'] >= 2) {
                    $row['level2'] = $info['level2'];
                }
                if ($this->set['level'] >= 3) {
                    $row['level3'] = $info['level3'];
                }
                $row['credit1'] = m('member')->getCredit($row['openid'], 'credit1');
                $row['credit2'] = m('member')->getCredit($row['openid'], 'credit2');
                $row['commission_total'] = $info['commission_total'];
                $row['commission_pay'] = $info['commission_pay'];
                $row['followed'] = m('user')->followed($row['openid']);
                if ($row['agentid'] == $member['id']) {
                    $row['level'] = 1;
                } else if (in_array($row['agentid'], array_keys($member['level1_agentids']))) {
                    $row['level'] = 2;
                } else if (in_array($row['agentid'], array_keys($member['level2_agentids']))) {
                    $row['level'] = 3;
                }
            }
        }
        unset($row);

        if ($_GPC['export'] == 1) {
            foreach ($list as &$row) {
                $row['realname'] = str_replace('=', "", $row['realname']);
                $row['nickname'] = str_replace('=', "", $row['nickname']);
                $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
                $row['agentime'] = empty($row['agenttime']) ? '' : date('Y-m-d H:i', $row['agentime']);
                $row['groupname'] = empty($row['groupname']) ? '无分组' : $row['groupname'];
                $row['levelname'] = empty($row['levelname']) ? '普通等级' : $row['levelname'];
                $row['parentname'] = empty($row['parentname']) ? '总店' : "[" . $row['agentid'] . "]" . $row['parentname'];
                $row['statusstr'] = empty($row['status']) ? '' : "通过";
                $row['followstr'] = empty($row['followed']) ? '' : "已关注";

                if (p('diyform')  && !empty($row['diymemberfields']) && !empty($row['diymemberdata'])) {
                    $diyformdata_array = p('diyform')->getDatas(iunserializer($row['diymemberfields']), iunserializer($row['diymemberdata']));
                    $diyformdata = "";
                    foreach ($diyformdata_array as $da) {
                        $diyformdata.=$da['name'] . ": " . $da['value'] . "\r\n";
                    }
                    $row['member_diyformdata'] = $diyformdata;
                }

                if (p('diyform')  && !empty($row['diycommissionfields']) && !empty($row['diycommissiondata'])) {
                    $diyformdata_array = p('diyform')->getDatas(iunserializer($row['diycommissionfields']), iunserializer($row['diycommissiondata']));
                    $diyformdata = "";
                    foreach ($diyformdata_array as $da) {
                        $diyformdata.=$da['name'] . ": " . $da['value'] . "\r\n";
                    }
                    $row['agent_diyformdata'] = $diyformdata;
                }
            }
            unset($row);

            $columns = array(
                array('title' => 'ID', 'field' => 'id', 'width' => 12),
                array('title' => '昵称', 'field' => 'nickname', 'width' => 12),
                array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                array('title' => '微信号', 'field' => 'weixin', 'width' => 12),
                array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                array('title' => '推荐人', 'field' => 'parentname', 'width' => 12),
                array('title' => '分销商等级', 'field' => 'levelname', 'width' => 12),
                array('title' => '点击数', 'field' => 'clickcount', 'width' => 12),
                array('title' => '下线分销商总数', 'field' => 'levelcount', 'width' => 12),
                array('title' => '一级下线分销商数', 'field' => 'level1', 'width' => 12),
                array('title' => '二级下线分销商数', 'field' => 'level2', 'width' => 12),
                array('title' => '三级下线分销商数', 'field' => 'level3', 'width' => 12),
                array('title' => '累计佣金', 'field' => 'commission_total', 'width' => 12),
                array('title' => '打款佣金', 'field' => 'commission_pay', 'width' => 12),
                array('title' => '注册时间', 'field' => 'createtime', 'width' => 12),
                array('title' => '成为分销商时间', 'field' => 'createtime', 'width' => 12),
                array('title' => '审核状态', 'field' => 'createtime', 'width' => 12),
                array('title' => '是否关注', 'field' => 'followstr', 'width' => 12)
            );

            if (p('diyform')) {
                $columns[] = array('title' => '分销商会员自定义信息', 'field' => 'member_diyformdata', 'width' => 36);
                $columns[] = array('title' => '分销商申请自定义信息', 'field' => 'agent_diyformdata', 'width' => 36);
            }

            m('excel')->export($list, array(
                "title" => "推广下线-" . date('Y-m-d-H-i', time()),
                "columns" => $columns
            ));
        }



        load()->func('tpl');
        include $this->template('commission/agent_user');
    }

    function query() {
        global $_W, $_GPC;
        $kwd = trim($_GPC['keyword']);
        $wechatid = intval($_GPC['wechatid']);
        if (empty($wechatid)) {
            $wechatid = $_W['uniacid'];
        }
        $params = array();
        $params[':uniacid'] = $wechatid;
        $condition = " and uniacid=:uniacid and isagent=1 and status=1";
        if (!empty($kwd)) {
            $condition.=" AND ( `nickname` LIKE :keyword or `realname` LIKE :keyword or `mobile` LIKE :keyword )";
            $params[':keyword'] = "%{$kwd}%";
        }
        if (!empty($_GPC['selfid'])) {
            $condition.=" and id<>" . intval($_GPC['selfid']);
        }
        $ds = pdo_fetchall('SELECT id,avatar,nickname,openid,realname,mobile FROM ' . tablename('ewei_shop_member') . " WHERE 1 {$condition} order by createtime desc", $params);


        //替换昵称中的特殊字符 '
        foreach ($ds as $key=>$val){
            $ds[$key]['nickname']=str_replace("'", "", $ds[$key]['nickname']);
        }



        include $this->template('commission/query');
    }

    function check() {
        global $_W, $_GPC;

        $id = intval($_GPC['id']);


        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $status = intval($_GPC['status']);
        $members = pdo_fetchall("SELECT id,openid,agentid,nickname,realname,mobile,status FROM " . tablename('ewei_shop_member') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);
        $time = time();
        foreach ($members as $member) {
            if ($member['status'] === $status) {
                continue;
            }
            if ($status == 1) {
                pdo_update('ewei_shop_member', array('status' => 1, 'agenttime' => $time), array('id' => $member['id'], 'uniacid' => $_W['uniacid']));

                plog('commission.agent.check', "审核分销商 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");

                //成为分销商消息通知 
                $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'agenttime' => $time), TM_COMMISSION_BECOME);

                //检测升级	
                if (!empty($member['agentid'])) {
                    $this->model->upgradeLevelByAgent($member['agentid']);

                    //股东升级
                    if(p('globonus')){
                        p('globonus')->upgradeLevelByAgent($member['agentid']);
                    }
                    //创始人升级
                    if(p('author')){
                        p('author')->upgradeLevelByAgent($member['agentid']);
                    }

                }
            } else {
                pdo_update('ewei_shop_member', array('status' => 0, 'agenttime' => 0), array('id' => $member['id'], 'uniacid' => $_W['uniacid']));
                plog('commission.agent.check', "取消审核 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
            }
        }
        show_json(1, array('url' => referer()));
    }

    function agentblack() {

        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (empty($id)) {
            $id = is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0;
        }
        $agentblack = intval($_GPC['agentblack']);
        $members = pdo_fetchall("SELECT id,openid,nickname,realname,mobile,agentblack FROM " . tablename('ewei_shop_member') . " WHERE id in( $id ) AND uniacid=" . $_W['uniacid']);
        foreach ($members as $member) {
            if ($member['agentblack'] === $agentblack) {
                continue;
            }
            if ($agentblack == 1) {
                pdo_update('ewei_shop_member', array('agentblack' => 1), array('id' => $member['id']));
                plog('commission.agent.agentblack', "设置黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
            } else {
                pdo_update('ewei_shop_member', array('agentblack' => 0), array('id' => $member['id']));
                plog('commission.agent.agentblack', "取消黑名单 <br/>分销商信息:  ID: {$member['id']} /  {$member['openid']}/{$member['nickname']}/{$member['realname']}/{$member['mobile']}");
            }
        }
        show_json(1, array('url' => referer()));
    }

}
