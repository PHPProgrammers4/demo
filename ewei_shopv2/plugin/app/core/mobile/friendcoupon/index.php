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


    public function main()
    {
        global $_W, $_GPC;
        $friendcouponModel = p('friendcoupon');
        $coupon = null;
        $activity = $friendcouponModel->getActivity($_GPC['id']);
        $user = $friendcouponModel->getMember($_W['openid']);
        $share_id = isset($_GPC['share_id']) ? $_GPC['share_id'] : null;
        $isShare = false;
        // 当前是否是分享链接
        if (isset($share_id) && !empty($share_id) && $share_id != $user['id']) {
            $isShare = true;
        }
        $tips = '';
        // 是否领取过任务
        $isReceive = false;
        // 活动是否已经成功
        $success = false;
        $activities = array();
        $activityData = array();
        $share_user = array();
        $shareParams = array('id' => $activity['id']);
        $mylink = '';


        // 活动不存在的情况, $tips==''是避免程序运行到最后只记录最后错误结果
        if (!$activity) {
            $tips == '' && $tips = '当前活动不存在';
        }
        // 验证活动时效性,如果有问题存储错误信息
        $activitySetting = $activity;

        if (!$friendcouponModel->validateActivity($_GPC['id'])) {
            if ($friendcouponModel->errno) {
                if ($isShare) {
                    $takePartInUsers = pdo_fetchall("select openid from " . tablename('ewei_shop_friendcoupon_data') . " where uniacid = {$_W['uniacid']} and headerid = {$share_id}");
                } else {
                    $takePartInUsers = pdo_fetchall("select openid from" . tablename('ewei_shop_friendcoupon_data') . " where uniacid = {$_W['uniacid']} and activity_id = {$activitySetting['id']}");
                }
                $openIds = array_column($takePartInUsers, 'openid');
                in_array($user['openid'], $openIds) && $tips = '';
            } else {
                $tips = $friendcouponModel->errmsg;
            }
        }


        $activitySetting['activity_start_time'] = $friendcouponModel->dateFormat($activitySetting['activity_start_time']);
        $activitySetting['activity_end_time'] = $friendcouponModel->dateFormat($activitySetting['activity_end_time']);
        $activitySetting['desc'] = explode("\r\n", $activitySetting['desc']);


        // 默认超时时间
        $overTime = 0;
        // 剩余人数默认是活动设置总人数
        $overPeople = $activitySetting['people_count'];

        // 是分享链接的话,处理
        if ($isShare) {
            $share_user = m('member')->getMember($share_id);
            $shareActivityInfo = $friendcouponModel->getCurrentActivityInfo($share_user['openid'], $activity['id']);
            $currentActivityInfo = $friendcouponModel->getCurrentActivityInfo($user['openid'], $activity['id']);
            // 分享人活动不存在的时候
            if (!$shareActivityInfo) {
                $tips == '' && $tips = '分享的活动已经不存在了哦';
            }

            if (!empty($currentActivityInfo) && $currentActivityInfo['headerid'] != $shareActivityInfo['headerid']) {
                $tips == '' && $tips = '您已经参与过这个活动啦，去看看别的活动吧~';
                $_GPC['share_id'] = null;
                $mylink = mobileUrl('friendcoupon', array(
                    'id' => $currentActivityInfo['activity_id'],
                    'share_id' => $currentActivityInfo['headerid']
                ));
            }


            if ($currentActivityInfo) {
                $isReceive = true;
                $activities = $friendcouponModel->getOngoingActivities($shareActivityInfo['activity_id'], $shareActivityInfo['headerid']);
                foreach ($activities as $activity) {
                    // 没有openid,则是当前没有参加的人
                    if (!$activity['openid']) {
                        $overPeople++;
                    }
                    // openid不是空,放入已经参与的用户中去
                    if ($activity['openid'] != '') {
                        $activityData[] = $activity;
                    }
                }
            }


            if ($currentActivityInfo['status'] == 1) {
                $success = true;
            } else { // 当前活动没有成功
                $activityData = array();
                // 当前还缺几个人瓜分成功
                $overPeople = 0;
                // 获取所有进行中的活动
                $activities = $friendcouponModel->getOngoingActivities($shareActivityInfo['activity_id'], $shareActivityInfo['headerid']);
                foreach ($activities as $activity) {
                    // 没有openid,则是当前没有参加的人
                    if (!$activity['openid']) {
                        $overPeople++;
                    }
                    // openid不是空,放入已经参与的用户中去
                    if ($activity['openid'] != '') {
                        $activityData[] = $activity;
                    }
                }
            }


        } else {

            $share_user = $user;
            // 不是分享链接
            // 获取当前用户的任务
            $currentActivityInfo = $friendcouponModel->getCurrentActivityInfo($user['openid'], $activity['id']);
            // 如果存在当前任务,认为用户领取了任务
            if ($currentActivityInfo) {
                $isReceive = true;
            }
            // 任务成功之后会把所有的status更新成1,所以当前任务成功认为所有任务都成功了
            if ($currentActivityInfo['status'] == 1) {
                $success = true;
                $activities = $friendcouponModel->getOngoingActivities($currentActivityInfo['activity_id'], $currentActivityInfo['headerid']);
                foreach ($activities as $activity) {
                    // 没有openid,则是当前没有参加的人
                    if (!$activity['openid']) {
                        $overPeople++;
                    }
                    // openid不是空,放入已经参与的用户中去
                    if ($activity['openid'] != '') {
                        $activityData[] = $activity;
                    }
                }
            } else { // 当前活动没有成功
                // 当前还缺几个人瓜分成功
                $overPeople = 0;
                // 获取所有进行中的活动
                $activities = $friendcouponModel->getOngoingActivities($currentActivityInfo['activity_id'], $currentActivityInfo['headerid']);
                foreach ($activities as $activity) {
                    // 没有openid,则是当前没有参加的人
                    if (!$activity['openid']) {
                        $overPeople++;
                    }
                    // openid不是空,放入已经参与的用户中去
                    if ($activity['openid'] != '') {
                        $activityData[] = $activity;
                    }
                }
            }
        }


        $shareParams['share_id'] = $share_user['id'];

        if ($success) {
            $coupon = pdo_fetch("select id,couponid,openid,uniacid,used from " . tablename('ewei_shop_coupon_data') . " where friendcouponid = :friendcouponid", array('friendcouponid' => $currentActivityInfo['id']));
        }

        // 当前活动已经完成的时候
        if ($currentActivityInfo['status'] == 1 || $shareActivityInfo['status'] == 1) {
            $takePartInUserIds = array_column($activityData, 'openid');
            if (!in_array($user['openid'], $takePartInUserIds)) {
                $tips == '' && $tips = '当前活动已经瓜分成功,下回要快点哦~';
            }
        }

        // 任务已经接受,但是没有成功,并且已经超过活动最后期限
        if (time() > $currentActivityInfo['deadline'] && !$success && $isReceive) {
            $tips == '' && $tips = '很遗憾，没有在规定时间内完成瓜分，下次要快一点哦~';
        }


        if ($activities) {
            $overTime = $activities[0]['deadline'];
        } else {
            $overTime = 0;
        }

        // 清除分享人id
        if ($tips != '') {
            $_GPC['share_id'] = null;
        }


        return app_json(array(
            'coupon' => $coupon,
            'success' => $success,
            'isReceive' => $isReceive,
            'isShare' => $isShare,
            'currentActivityInfo' => $currentActivityInfo,
            'overTime' => $overTime,
            'overPeople' => $overPeople,
            'activityData' => $activityData,
            'activitySetting' => $activitySetting,
            'share_user' => $share_user,
            'invalidMessage' => $tips,
            'isLogin' => (boolean)$user,
            'mylink' => $mylink
        ));
    }

    public function receive()
    {
        global $_W, $_GPC;
        $friendcouponModel = p('friendcoupon');
        // 当前用户
        $user = $friendcouponModel->getMember($_GPC['openid']);
        // 活动id
        $activity_id = (int)$_GPC['id'];
        $form_id = $_GPC['form_id'];
        $activity = $friendcouponModel->validateActivity($activity_id);
        //TODO
        if (!$user) {
            $openidwx = substr($_GPC['openid'],7);
            $user = $friendcouponModel->getmemberwx($openidwx);
        }
        if (!$user){
            return app_error(83003, "请先获取登录授权!");
        }

        if (!$activity) {
            return app_error(83004, $friendcouponModel->errmsg);
        }


        $currentUserActivity = $friendcouponModel->getCurrentActivityInfo($user['openid'], $activity['id']);
        // 记录不存在的话,生成随机金额优惠券,写入data表
        if ($currentUserActivity) {
            app_error(10001, '您当前已经领取过任务了,赶快分享给好友吧');
        }
        // 计算出来的优惠券金额
        $couponAmounts = array();
        switch ($activity['allocate']) {
            case 0:
                $couponAmounts = $friendcouponModel->randomCouponAlgorithm($activity['coupon_money'], $activity['people_count'], $activity['upper_limit']);
                break;
            case 1:
                $couponAmounts = $friendcouponModel->avgCouponAlgorithm($activity['coupon_money'], $activity['people_count']);
                break;
        }

        $time = time();

        // 获得所有进行中的活动
        $couponActivities = $friendcouponModel->getOngoingActivities($currentUserActivity['id'], $currentUserActivity['headerid']);
        // 下发所有瓜分券,空出openid,后面刮一个给塞一个数据，
        $currentActivityIds = array();
        if (!$couponActivities) {
            $deadline = $time + $activity['duration'] * 3600 < $activity['activity_end_time'] ?
                $time + $activity['duration'] * 3600 :
                $activity['activity_end_time'];
            foreach ($couponAmounts as $couponAmount) {
                $data = array(
                    'uniacid' => $_W['uniacid'],
                    'activity_id' => $activity['id'],
                    'headerid' => $user['id'],
                    'status' => 0, // 正常状态
                    'deduct' => (float)$couponAmount,
                    'enough' => $activity['use_condition'], //满多少可用,不填写相当于空，对应
                    'deadline' => $deadline
                );
                // 生成所有随机优惠券
                pdo_insert('ewei_shop_friendcoupon_data', $data);
                $currentActivityIds[] = pdo_insertid();
            }
        } else {
            $currentActivityIds[] = array_column($couponActivities, 'id');
        }

        // 找出最小的id,
        $headerid = min($currentActivityIds);
        // 把优惠券先发给队长
        pdo_update('ewei_shop_friendcoupon_data', array(
            'openid' => $user['openid'],
            'avatar' => $user['avatar'],
            'nickname' => $user['nickname'],
            'receive_time' => time(), // 领取活动的时间
            'form_id' => $form_id
        ), array('id' => $headerid));
        // 发起次数-1
        pdo_update('ewei_shop_friendcoupon', array('launches_count +=' => 1), array('id' => $activity['id']));
        return app_json('活动领取成功');
    }

    public function divide()
    {
        global $_W, $_GPC;
        $activity_id = $_GPC['id'];
        $friendcouponModel = p('friendcoupon');
        // 当前用户
        $share_id = $_GPC['share_id']; // 活动发起者Id
        $form_id = $_GPC['form_id']; // formid
        $share_user = $friendcouponModel->getMember($share_id);
        $user = $friendcouponModel->getMember($_GPC['openid']);
        $activity = $friendcouponModel->getActivity($activity_id);

        if (!$user) {
            return app_error(83003, "请登陆后在进行操作!");
        }

        if (!$activity) {
            return app_error(83004, $friendcouponModel->errmsg);
        }

        // 查看当前活动是否还在
        $onGoingActivities = $friendcouponModel->getOngoingActivities($activity_id, $share_id);

        if (!$onGoingActivities) {
            return app_error(83005, array('没有进行中的活动'));
        }
        // 获取当前分享的活动的信息
        $shareActivityInfo = $friendcouponModel->getCurrentActivityInfo($share_user['openid'], $activity['id']);

        if ($shareActivityInfo['status'] == 1) {
            return app_error(83006, '活动已经完成<br>下次早点来啊~');
        }
        // 看下当前用户是否参与了活动
        $currentActivityInfo = $friendcouponModel->getCurrentActivityInfo($user['openid'], $activity_id);
        if (!$currentActivityInfo) {
            // 获取所有的优惠券
            $coupons = pdo_fetchall("select * from " . tablename('ewei_shop_friendcoupon_data') . " where uniacid = :uniacid and activity_id = :activity_id and headerid = :headerid", array(
                ':uniacid' => $_W['uniacid'],
                ':activity_id' => $activity['id'],
                ':headerid' => $share_id,
            ));
            foreach ($coupons as $coupon) {
                // 没有openid的就是没有下发的优惠券,找到对应记录,更新,然后跳转
                if (!$coupon['openid']) {
                    pdo_update('ewei_shop_friendcoupon_data', array(
                        'openid' => $user['openid'],
                        'status' => 0,
                        'avatar' => $user['avatar'],
                        'nickname' => $user['nickname'],
                        'receive_time' => time(), // 领取活动的时间
                        'form_id' => $form_id
                    ), array('id' => $coupon['id']));
                    break;
                }
            }

            // 获取下剩余优惠券数量
            $overPlus = pdo_fetchcolumn("select count(1) from " . tablename('ewei_shop_friendcoupon_data') . " where uniacid = :uniacid and headerid = :headerid and activity_id = :activity_id and openid = ''", array(
                ':uniacid' => $_W['uniacid'],
                ':headerid' => $share_id,
                ':activity_id' => $activity_id,
            ));

            // 如果还没有完全发完,就进入分享页面等待
            if ($overPlus) {
                return app_json(array('message' => '瓜分成功！'));
            }
            pdo_update('ewei_shop_friendcoupon_data', array('status' => 1), array('activity_id' => $activity_id, 'headerid' => $share_id));
            // 整个活动就成功了
            //重新获取下优惠券
            $sql = "select f.*, fd.enough, fd.id as friendcouponid,fd.deduct,fd.form_id,fd.openid,fd.enough from" . tablename('ewei_shop_friendcoupon_data') . " fd "
                . " left join " . tablename('ewei_shop_friendcoupon') . " f on fd.activity_id = f.id"
                . " where fd.uniacid = :uniacid and fd.headerid = :headerid and fd.activity_id = :activity_id";

            $coupons = pdo_fetchall($sql, array(
                ':uniacid' => $_W['uniacid'],
                ':activity_id' => $activity['id'],
                ':headerid' => $share_id
            ));

            // 发送优惠券
            if (false === $friendcouponModel->sendFriendCoupon($coupons)) {
                return app_error(83007, $friendcouponModel->errmsg);
            }

            //发送小程序模板消息通知
            foreach ($coupons as $coupon) {
                $openid = str_replace('sns_wa_', '', $coupon['openid']);
                $templateMessage = $friendcouponModel->getTemplateMessage('complete', true);
                $template_id = $templateMessage['templateid'];
                $page = 'friendcoupon/index?id=' . $activity_id . '&share_id=' . $share_id;

                // 瓜分券名称
                if ($coupon['enough'] > 0) {
                    $couponName = "满{$coupon['enough']}减{$coupon['deduct']}元优惠券";
                } else {
                    $couponName = "无门槛减{$coupon['deduct']}元优惠券";
                }

                $datas = unserialize($templateMessage['datas']);
                $data = array();

                foreach ($datas as $index => $item) {
                    $key = trim($item['key'], "{{}}"); // keyword1.DATA
                    $key = explode('.', $key)[0]; // keyword1
                    $value = $item['value'];
                    preg_match_all('#\[\S*?]#', $item['value'], $matchResult);
                    // "[活动名称]将于[活动开始时间]开始" => [活动名称] [活动开始时间]
                    $matches = $matchResult[0];
                    // 遍历所有匹配结果,并把结果替换掉
                    foreach ($matches as $match) {
                        switch ($match) {
                            case "[活动名称]":
                                $replaceElement = $activity['title'];
                                break;
                            case "[活动开始时间]":
                                $replaceElement = $friendcouponModel->dateFormat($activity['activity_start_time']);
                                break;
                            case "[活动结束时间]":
                                $replaceElement = $friendcouponModel->dateFormat($activity['activity_end_time']);
                                break;
                            case "[瓜分券名称]":
                                $replaceElement = $couponName;
                                break;
                            case "[瓜分券领取时间]":
                                $replaceElement = $friendcouponModel->dateFormat(time());
                                break;
                            default:
                                break;
                        }

                        $value = str_replace($match, $replaceElement, $value);
                    }

                    $data[$key] = array(
                        'value' => $value
                    );
                }
                $emphasis_keyword = null;
                if ($templateMessage['emphasis_keyword'] != -1) {
                    $emphasis_keyword = trim($datas[$templateMessage['emphasis_keyword']]['key'], "{{}}");
                }

                $sendResult = $this->sendTemplateMessage($openid, $template_id, $page, $coupon['form_id'], $data, $emphasis_keyword);
                $sendResult = json_decode($sendResult, true);
                if ($sendResult['errcode'] === 0) {
                    pdo_update('ewei_shop_friendcoupon_data', array('is_send' => 1), array('id' => $coupon['friendcouponid']));
                }
            }

            // 下发所有优惠券
            return app_json(array('message' => '活动完成！'));
        }
        return app_error(83008, '您已经瓜分过优惠券了,请不要重复瓜分!');
    }

    /**
     * 检查用户登陆状态
     */
    protected function getMember($openid)
    {
        return m('member')->getMember($openid);
    }

    /**
     * 发送客服模板消息
     * @param $openid
     * @param $template_id
     * @param $page
     * @param $form_id
     * @param null $data
     * @param null $emphasis_keyword
     * @return array
     */
    private function sendTemplateMessage($openid, $template_id, $page, $form_id, $data = null, $emphasis_keyword = null)
    {
        $token = p('app')->getAccessToken();
        $data = json_encode([
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => $data,
            'emphasis_keyword' => $emphasis_keyword
        ]);

        $result = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $token, $data);
        return $result['content'];
    }

    // 查看更多好友
    public function more()
    {
        global $_W, $_GPC;

        $friendcouponModel = p('friendcoupon');
        $activity_id = $_GPC['id']; // 活动id
        $share_id = $_GPC['share_id']; // 分享人id
        // 通过上面两个字段获取一个确定的活动
        $pindex = max(1, $_GPC['pindex']);
        $psize = 10;

        $openid = $_GPC['openid'];


        // 当前正在参与的活动
        if (empty($share_id)) {
            $currentTakePartActivity = pdo_fetch("select * from " . tablename('ewei_shop_friendcoupon_data') . " where uniacid = :uniacid and openid = :openid and activity_id = :activity_id", array(
                ':uniacid' => $_W['uniacid'],
                ':openid' => $openid,
                ':activity_id' => $activity_id,
            ));
            $share_id = $currentTakePartActivity['headerid'];
        }


        $list = pdo_fetchall("select avatar,nickname,deduct from " . tablename('ewei_shop_friendcoupon_data') . " where uniacid = :uniacid and activity_id = :activity_id and headerid = :headerid and openid <> '' limit " . ($pindex - 1) . "," . $psize, array(
            ':uniacid' => $_W['uniacid'],
            ':headerid' => $share_id,
            ':activity_id' => $activity_id
        ));

        show_json(0, array(
            'list' => $list
        ));
    }

}