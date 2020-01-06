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
require __DIR__ . '/base.php';

class Index_EweiShopV2Page extends Base_EweiShopV2Page
{
    /**
     *     团长申请
     */
    public  function register(){
        global $_W,$_GPC;

        $openid = $_W['openid'];
        $set = set_medias($this->set, 'regbg');
        $area_set = m('util')->get_area_config_set();
        $new_area = intval($area_set['new_area']);
        $member = m('member')->getMember($openid);

        //不是分销商跳转到分销商申请
        if($member['isagent'] == 0 || $member['status'] == 0){
            return app_error(AppError::$CommissionIsNotAgent);
        }

        if ($member['isheads'] == 1 && $member['headsstatus'] == 1) {
            return app_error(AppError::$DividendAgent);
        }

        if ($member['headsblack']) {
            return app_error(AppError::$UserIsBlack);
        }

        $apply_set = array();
        $apply_set['open_protocol'] = $set['open_protocol'];
        if (empty($set['applytitle'])) {
            $apply_set['applytitle'] = '分销商申请协议';
        } else {
            $apply_set['applytitle'] = $set['applytitle'];
        }


        //自定义表单
        $template_flag = 0;
        $diyform_plugin = p('diyform');
        if ($diyform_plugin) {
            $set_config = $diyform_plugin->getSet();
            $dividend_diyform_open = $set_config['dividend_diyform_open'];
            if ($dividend_diyform_open == 1) {
                $template_flag = 1;
                $diyform_id = $set_config['dividend_diyform'];
                if (!empty($diyform_id)) {
                    $formInfo = $diyform_plugin->getDiyformInfo($diyform_id);
                    $fields = $formInfo['fields'];
                    $diyform_data = iunserializer($member['diycommissiondata']);
                    $f_data = $diyform_plugin->getDiyformData($diyform_data, $fields, $member);
                }
            }
        }

        if($diyform_plugin){
            $appDatas = $diyform_plugin->wxApp($fields, $f_data, $this->member);
        }


        if ($_W['ispost']) {
            if ($set['condition'] != '0' ) {
                show_json(0, '未开启' . $set['texts']['agent'] . "注册!");
            }

            $check = intval($set['check']);
            $ret['headsstatus'] = $check;

            if ($template_flag == 1) {
                $memberdata = $_GPC['memberdata'];
                $insert_data = $diyform_plugin->getInsertData($fields, $memberdata);
                $data = $insert_data['data'];
                $m_data = $insert_data['m_data'];
                $mc_data = $insert_data['mc_data'];

                $m_data['diyheadsid'] = $diyform_id;
                $m_data['diyheadsfields'] = iserializer($fields);
                $m_data['diyheadsdata'] = $data;

                $m_data['isheads'] = 1;
                $m_data['headsstatus'] = $check;
                $m_data['headstime'] = $check == 1 ? time() : 0;

                unset($m_data['credit1'], $m_data['credit2']);
                pdo_update('ewei_shop_member', $m_data, array('id' => $member['id']));

                if ($check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'headstime' => $m_data['headstime']), TM_DIVIDEND_BECOME);
                }

                if (!empty($member['uid'])) {
                    if (!empty($mc_data)) {
                        unset($mc_data['credit1'], $mc_data['credit2']);
                        m('member')->mc_update($member['uid'], $mc_data);
                    }
                }
            } else {

                $data = array(
                    'isheads' => 1,
                    'headsstatus' => $check,
                    'realname' => $_GPC['realname'],
                    'mobile' => $_GPC['mobile'],
                    'headstime' => $check == 1 ? time() : 0
                );
                pdo_update('ewei_shop_member', $data, array('id' => $member['id']));
                if ($check == 1) {
                    $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'headstime' => $data['headstime']), TM_DIVIDEND_BECOME);
                    if (!empty($mid)) {

                    }
                }
                if (!empty($member['uid'])) {
                    //更新会员
                    m('member')->mc_update($member['uid'], array('realname' => $data['realname'], 'mobile' => $data['mobile']));
                }
            }
            return app_json(0, array('check' => $check));
        }

        //以前未得到，修改了条件现在达到，重新判断资格
        $to_check_heads  =false;

        //成为团长条件
        if ($set['condition'] == 1) { //一级下线人数
            $status = 1;
            $membercount = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . " where  agentid = ".$member['id']." and id <> ".$member['id']."  and uniacid=:uniacid  limit 1", array(':uniacid' => $_W['uniacid']));
            if ($membercount < intval($set['downline'])) {
                //未达到数量
                $status = 0;
                $member_count = number_format($membercount, 0);
                $member_totalcount = number_format($set['downline'], 0);
            } else{
                //以前未达到，现在改变条件达到了
                $to_check_heads = true;
            }
        } else if ($set['condition'] == 2) { //一级下线分销商数
            $status = 1;
            $commissiondownlinecount = pdo_fetchcolumn('select count(*) from ' . tablename('ewei_shop_member') . " where  agentid = ".$member['id']." and isagend = 1 and id <> ".$member['id']."  and uniacid=:uniacid  limit 1", array(':uniacid' => $_W['uniacid']));
            if ($commissiondownlinecount < floatval($set['commissiondownline'])) {
                //未达到数量
                $status = 0;
                $commissiondownline_count = number_format($commissiondownlinecount, 0);
                $commissiondownline_totalcount = number_format($set['commissiondownline'], 0);
            } else {
                //以前未达到，现在改变条件达到了
                $to_check_heads = true;
            }
        } else if ($set['condition'] == 3) {  //佣金总额
            $status = 1;
            $commission_info = p('commission') -> getInfo($member['openid'],array('total'));
            $totalcommissioncount = $commission_info['commission_total'];
            if($totalcommissioncount < floatval($set['total_commission'])){
                //未达到数量
                $status = 0;
                $total_commission_count = number_format($totalcommissioncount, 0);
                $total_commission_totalcount = number_format($set['total_commission'], 0);
            }else{
                //以前未达到，现在改变条件达到了
                $to_check_heads = true;
            }
        }else if($set['condition'] == 4){  //提现佣金总额
            $status = 1;
            $commission_info = p('commission') -> getInfo($member['openid'],array('pay'));
            $cashcommissioncount = $commission_info['commission_pay'];
            if($cashcommissioncount < floatval($set['cash_commission'])){
                //未达到数量
                $status = 0;
                $cash_commission_count = number_format($cashcommissioncount, 0);
                $cash_commission_totalcount = number_format($set['cash_commission'], 0);
            }else{
                //以前未达到，现在改变条件达到了
                $to_check_heads = true;
            }
        }

        //自动升级为团长
        if($to_check_heads) {
            if (empty($member['isheads'])) {
                $data = array(
                    'isheads' => 1,
                    'headsstatus' => 0,
                    'headstime' => time()
                );
                $heads_data['headsid'] = $member['id'];
                $heads_data['uniacid'] = $_W['uniacid'];
                $heads_data['status'] = 0;
                pdo_update('ewei_shop_member', $data, array('id' => $member['id']));
                pdo_insert('ewei_shop_dividend_init',$heads_data);
//                if($check==1){
                $this->model->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'headstime' => $data['headstime']), TM_DIVIDEND_BECOME);
//                }
            }
        }

        $result = array(
            'set'=>array(
                'open' => $set['open'],
                'texts' => $this->set['texts'],
                'regbg' => empty($set['regbg'])? $_W['siteroot'].'addons/ewei_shopv2/plugin/dividend/template/mobile/default/static/images/banner.jpg':$set['regbg'],
                'condition' => (int)$set['condition'],
                'open_protocol' => $set['open_protocol'],
                'applytitle' => $set['applytitle'],
                'applycontent' => $set['applycontent'],
                'register_bottom' => $set['register_bottom'],
                'register_bottom_title1' => $set['register_bottom_title1'],
                'register_bottom_content1' => $set['register_bottom_content1'],
                'register_bottom_title2' => $set['register_bottom_title2'],
                'register_bottom_content2' => $set['register_bottom_content2'],
                'register_bottom_content' => $set['register_bottom_content'],
            ),
            'member'=>array(
                'headsblack'=>(int)$member['headsblack'],
                'isheads'=>(int)$member['isheads'],
                'headsstatus'=>(int)$member['headsstatus'],
                'realname'=>$member['realname'],
                'mobile'=>$member['mobile'],
            ),
            'status' => intval($status),
            'member_totalcount' => $member_totalcount, //需要达到多少下线
            'member_count' => $member_count,  //已经拥有的下线
            'commissiondownline_totalcount' => $commissiondownline_totalcount,  //需要多少下线是分销商
            'commissiondownline_count' => $commissiondownline_count,  //已有多少下线是分销商
            'total_commission_totalcount' => $total_commission_totalcount,  //需要达到累计佣金数量
            'total_commission_count' => $total_commission_count,  //已达到累计佣金数量
            'cash_commission_totalcount' => $cash_commission_totalcount,  //需达到提现佣金数量
            'cash_commission_count' => $cash_commission_count,  //已提现佣金数量
            'template_flag'=>$template_flag,
            'f_data' => $appDatas['f_data'],
            'fields' => $appDatas['fields'],
        );

        return app_json($result);
    }

    /**
     *  分红中心
     */
    public function main(){
        global $_GPC,$_W;

        $member = $this->model->getInfo($_W['openid'], array('total', 'ordercount0', 'ok', 'ordercount', 'wait', 'pay'));

        //提现笔数
        $member['applycount'] = pdo_fetchcolumn('select count(id) from ' . tablename('ewei_shop_dividend_apply') . ' where mid=:mid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':mid' => $member['id']));

        $initData = pdo_fetch('select * from '.tablename('ewei_shop_dividend_init').' where headsid = :headsid and uniacid = :uniacid',array(':headsid'=>$member['id'],':uniacid'=>$_W['uniacid']));
        $isbuild = $initData['status'];

        //提现明细
        $member['applycount'] = pdo_fetchcolumn('select count(id) from ' . tablename('ewei_shop_dividend_apply') . ' where mid=:mid and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':mid' => $member['id']));

        $result = array(
            'member' => $member,
            'isbuild' => $isbuild,
            'set'=>array(
                'texts' => $this->set['texts']
            ),
        );

        return app_json($result);
    }


    /**
     *  创建团队
     */
    public  function createTeam(){
        global $_W,$_GPC;
        $member = m('member') -> getMember($_W['openid']);
        if(empty($member['isheads']) || empty($member['headsstatus'])){
            return app_error(1,'您还不是团长');
        }
        $data = pdo_fetchall('select id from '.tablename('ewei_shop_commission_relation').' where pid = :pid',array(':pid'=>$member['id']));
        if(!empty($data)){

            $ids = array();
            foreach($data as $k => $v){
                $ids[] = $v['id'];
            }
            pdo_update('ewei_shop_member', array("headsid"=>$member['id']), array('id' =>$ids));
            pdo_update('ewei_shop_dividend_init',array('status'=>1),array('headsid'=>$member['id']));
            $arr['message'] = '创建完成';
            return app_json($arr);
        }else{
            pdo_update('ewei_shop_dividend_init',array('status'=>1),array('headsid'=>$member['id']));
            $arr['message'] = '创建完成';
            return app_json($arr);
        }

    }
}