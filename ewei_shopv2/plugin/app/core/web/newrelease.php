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

class Newrelease_EweiShopV2Page extends PluginWebPage
{

    private $key = 'asdf734JH3464tr56GJ';

    public function main()
    {
        global $_W;

        // 小程序状态 1 未提交 2已提交 3 审核中 4审核 5发布 6审核不通过
        $error = null;
        // 获取授权状态
        $auth = $this->getAuth();
        if (is_error($auth)) {
            $error = $auth['message'];
        } else {
            $is_auth = is_array($auth) ? $auth['is_auth'] : false;
            $authUrl = EWEI_SHOPV2_AUTH_WXAPP . 'auth/auth?id=' . $auth['id'];
            if ($is_auth) {
                $release = $this->model->getRelease($auth['id']);
            }
            $list = $this->model->getReleaseList();
            if (is_error($list)) {
                $error = $list['message'];
            } elseif (empty($list)) {
                $error = '未查询到授权小程序';
            }

            //提交记录
            $log = pdo_fetchall('select * from ' . tablename('ewei_shop_upwxapp_log') . " where uniacid=:uniacid and type=1 order by id desc", array(':uniacid' => $_W['uniacid']));
            $test_code = IA_ROOT . "/addons/ewei_shopv2/plugin/app/static/images/test_code_" . $_W['uniacid'] . ".jpg";

            //临时二维码
            $version_time = 0;
            //如果目录为空 或者 当前时间大于上次修改时间+(25分钟-10秒) 则过期
            if (!filemtime($test_code) || ((filemtime($test_code) + 1490) < time())) {
                $is_expire = 1;//过期
            } else {
                $version_time = filemtime($test_code);
            }

            //小程序二维码
            $wxcode = IA_ROOT . "/addons/ewei_shopv2/plugin/app/static/images/wxcode_" . $_W['uniacid'] . ".jpg";
            //如果目录为空 或者 上次修改时间大于两小时 则重新获取二维码
            if (!filemtime($wxcode) || ((filemtime($wxcode) + 7200) < time())) {
                $accessToken = $this->model->getAccessToken();
                if (is_error($accessToken)) {
                    $error = $accessToken['message'];
                } else {
                    load()->func('communication');
                    // 获取小程序二维码 https://developers.weixin.qq.com/miniprogram/dev/api/qrcode.html    接口A
//                    $result = ihttp_post('https://api.weixin.qq.com/wxa/getwxacode?access_token='. $accessToken,json_encode(array('path'=>'pages/index/index')));
                    //接口B
                    $result = ihttp_post('https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $accessToken, json_encode(array('scene' => 'index', 'path' => 'pages/index/index')));
                    file_put_contents($wxcode, $result['content']);
                }
            }
        }
        include $this->template();
    }

    public function upload()
    {
        global $_W, $_GPC;
        // 系统设置
        $sets = m('common')->getSysset(array('app'));
        $appid = $sets['app']['appid'];
        if (empty($appid)) {
            header('location: ' . webUrl('app/setting'));
        }
        //最后一条提交记录
        $last_log = pdo_fetch('select * from ' . tablename('ewei_shop_upwxapp_log') . " where uniacid=:uniacid and type=1 order by id desc limit 1", array(':uniacid' => $_W['uniacid']));
        @session_start();
        $ticket = $_SESSION['wxapp_new_ticket'];
        if (empty($ticket)) {
            $need_scan = 1;
            load()->func('communication');
            $res = ihttp_get(EWEI_SHOPV2_AUTH_WXAPP . 'generate/getqrcode');
            $content = json_decode($res['content'], true);
            if (!empty($content)) {
                $uuid = $content['uuid'];
                $qrcode = $content['qrcode'];
            }
        } else {
            $need_scan = 0;
        }
        $auth = get_auth();

        $response = ihttp_get(EWEI_SHOPV2_AUTH_WXAPP . 'generate/check-version?site_id=' . $auth['id']);

        if (!empty($response['content'])) {
            $content = $response['content'];
            $ret = json_decode($content, true);
            if ($ret['errno'] == -1) {
                $this->message($ret['errmsg']);
            }
        }


        include $this->template();
    }

    public function getstatus()
    {
        global $_W, $_GPC;
        load()->func('communication');
        $uuid = $_GPC['uuid'];
        if (empty($uuid)) {
            show_json(0);
        }
        $res = ihttp_get(EWEI_SHOPV2_AUTH_WXAPP . 'generate/getstatus?uuid=' . $uuid);
        $content = json_decode($res['content'], true);

        if (empty($content['status'])) {
            show_json(0);
        }
        show_json(1, array(
            'wx_errcode' => $content['wx_errcode'],
            'wx_code' => $content['wx_code'],
        ));
    }


    public function getticket()
    {
        global $_W, $_GPC;

        load()->func('communication');
        $code = $_GPC['code'];
        if (empty($code)) {
            show_json(0);
        }
        $res = ihttp_get(EWEI_SHOPV2_AUTH_WXAPP . 'generate/getticket?code=' . $code);
        $content = json_decode($res['content'], true);

        if (!empty($content['status']) && !empty($content['new_ticket'])) {
            @session_start();
            $_SESSION['wxapp_new_ticket'] = $content['new_ticket'];
        } else {
            show_json(0, 'ticket获取失败');
        }

        show_json(1, array(
            'new_ticket' => $content['new_ticket']
        ));
    }


    public function submit()
    {
        global $_W, $_GPC;

        $version = $_GPC['version'];
        if (empty($version)) {
            show_json(0, '版本号不能为空！');
        }
        $describe = $_GPC['describe'];
        if (empty($describe)) {
            show_json(0, '版本描述不能为空！');
        }

        // 是否是好物圈
        $isGoods = intval($_GPC['is_goods']);

        @session_start();
        $ticket = $_SESSION['wxapp_new_ticket'];
        if (empty($ticket)) {
            show_json(0, 'ticket为空，请刷新后重试！');
        }

        $auth = $this->getAuth();
        if (is_error($auth)) {
            show_json(0, '未查询到授权信息！');
        }

        $tabBar = '';
        $app_set = m('common')->getSysset('app');
        if (!empty($app_set)) {
            if (!empty($app_set['tabbar'])) {
                $app_set['tabbar'] = iunserializer($app_set['tabbar']);
                if (!empty($app_set['tabbar'])) {
                    $tabBar = $app_set['tabbar'];
                }
            }
        }
        if (is_array($tabBar)) {
            if (is_array($tabBar['list'])) {
                foreach ($tabBar['list'] as $index => &$item) {
                    $item['pagePath'] = ltrim($item['pagePath'], '/');
                }
                unset($index, $item);
            }
            $tabBar = json_encode($tabBar);
        }


        //所有店铺装修页面元素的字符串
        $diy_str = '';
        $list = pdo_fetchall("SELECT `data` FROM " . tablename('ewei_shop_wxapp_page') . " WHERE uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
        foreach ($list as $li) {
            $diy_str .= base64_decode($li['data']);
        }

        //找出店铺装修自定义链接中的appid
        preg_match_all("/\"appid:(\w*)/", $diy_str, $appid_arr);
        $appIds = '';
        if (isset($appid_arr[1])) {
            $appid_arr[1] = array_values(array_unique($appid_arr[1]));
            $appIds = json_encode($appid_arr[1]);
        }

        load()->func('communication');
        ihttp_request(
            EWEI_SHOPV2_AUTH_WXAPP . 'generate/upload?id=' . $auth['id'],
            array(
                'version' => $version,
                'describe' => $describe,
                'tabBar' => $tabBar,
                'ticket' => $ticket,
                'appIds' => $appIds,
                'is_goods' => $isGoods
            ),
            array('Content-Type' => 'application/x-www-form-urlencoded'),
            3
        );
        $data['uniacid'] = $_W['uniacid'];
        $data['type'] = 1;
        $data['is_goods'] = $isGoods;
        $data['version'] = $version;
        $data['describe'] = $describe;
        $data['version_time'] = time();

        pdo_insert('ewei_shop_upwxapp_log', $data);
        show_json(1);
    }

    public function uploadstatus()
    {
        global $_W, $_GPC;

        $auth = $this->getAuth();
        if (is_error($auth)) {
            show_json(-1, '未查询到授权信息！');
        }
        load()->func('communication');
        $response = ihttp_get(EWEI_SHOPV2_AUTH_WXAPP . 'generate/uploadstatus?id=' . $auth['id']);
        if (empty($response)) {
            show_json(-1, '请刷新后重试！');
        } else {
            $data = json_decode($response['content'], true);
            if (intval($data['status']) == 202) {
                show_json(202);
            } else if (intval($data['status']) != 1) {
                if (intval($data['status']) == 402 || intval($data['status']) == 403) {
                    @session_start();
                    $_SESSION['wxapp_new_ticket'] = null;
                }
                show_json(-1, $data['errmsg']);
            } else {
                //小程序体验二维码
                $wxcode = IA_ROOT . "/addons/ewei_shopv2/plugin/app/static/images/test_code_" . $_W['uniacid'] . ".jpg";
                file_put_contents($wxcode, base64_decode($data['testcode']));
                show_json(1);
            }
        }
    }


    public function deletes()
    {

        global $_W, $_GPC;
        @session_start();
        $_SESSION['wxapp_new_ticket'] = null;
    }

    public function wechatset()
    {
        include $this->template();
    }

    public function getAuth()
    {
        global $_W, $_GPC;
        $key = 'app_auth' . $_W['uniacid'];
        @session_start();
        $auth = $_SESSION[$key];
        if (empty($auth) || is_error($auth)) {
            $auth = $this->model->getAuth();
            @session_start();
            $_SESSION[$key] = $auth;
        }
        return $auth;
    }
}