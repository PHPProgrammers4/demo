<?php
//dezend by http://www.yunlu99.com/

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Albb_EweiShopV2Page extends WebPage
{

    public $appkey = '7525739';
    public $appSecret = 'IxmtvyXXDzrk';
    public $access_token = '67f63cbb-ef47-4234-bc78-cea2693830c2';


    /*  1688：列表接口数据 */
    public function main()
    {
        global $_W;
        global $_GPC;

        $category = m("shop")->getFullCategory(true, true);


        $pindex = $_GPC['page'] ? $_GPC['page'] : 1;
        $psize = 20;

        $code_arr = array(
//        	'productId' =>  (int)$_GPC['keyword'] != 0?(int)$_GPC['keyword']:'',
            'biztype' => $_GPC['biztype'],//枚举;经营模式;1:生产加工,2:经销批发,3:招商代理,4:商业服务
            'buyerProtection' => $_GPC['buyerProtection'], //枚举;买家保障,多个值用逗号分割;qtbh:7天包换;swtbh:15天包换
            'city' => '', //所在地区- 市
            'deliveryTimeType' => $_GPC['deliveryTimeType'], //枚举;发货时间;1:24小时发货;2:48小时发货;3:72小时发货
            'descendOrder' => 'true', //是否倒序;正序: false;倒序:true
            'keyWords' => is_numeric($_GPC['keyword']) == false ||  strlen($_GPC['keyword']) < 10?(string)$_GPC['keyword']:'',   //搜索关键词
            'page' => $pindex, //页码
            'pageSize' => $psize, //页面数量;最大20
            'postCategoryId' => $_GPC['postCategoryId'],
            'priceStart' => $_GPC['priceStart'], //最低价
            'priceEnd' => $_GPC['priceEnd'], //最高价
            'priceFilterFields' => $_GPC['priceFilterFields'], //价格类型;默认分销价;agent_price:分销价;superBuyerPrice:超买价
            'province' => '', //所在地区- 省
            'sortType' => '', //枚举;排序字段;normal:综合;
            'tags' => $_GPC['tags'], //默认不传;商品标签,多个值用逗号分割;S级商品标: 263106;A级商品标:264898;B级商品标:266818;C级商品标:268866
            'offerTags' => $_GPC['offerTags'], //枚举;72514:进口货源;
            'offerIds' =>is_numeric($_GPC['keyword']) == true &&  strlen($_GPC['keyword']) >= 10?(int)$_GPC['keyword']:'', //商品id搜索，多个id用逗号分割
            'access_token' => $this->access_token,
        );

//        var_dump($code_arr);die;


        $apiInfo = 'param2/1/com.alibaba.p4p/alibaba.cps.op.searchCybOffers/' . $this->appkey;
        $json = file_get_contents($this->get_alibaba_api($this->appkey, $this->appSecret, $apiInfo, $code_arr));

        $json = json_decode($json, true);

        $list = $json['result']['result'];

        foreach ($list as &$value) {
            $goods = pdo_get('ewei_shop_goods', array('uniacid' => $_W['uniacid'], 'productId' => $value['offerId']));

            if (empty($goods)) {
                $value['status'] = -1;
                continue;
            } elseif ($goods['status'] == 1) {
                $value['status'] = 1;
            } elseif ($goods['status'] == 0) {
                $value['status'] = 0;

            }
            $value['cates'] = explode(',', $goods['cates']);
            $value['price'] = $goods['marketprice'];


        };
        unset($value);

        $total = $json['result']['totalCount'];

        $pager = pagination2($total, $pindex, $psize);
        include $this->template('goods/albb');
    }

    function follow_batch(){
        global $_W;
        global $_GPC;


    }


    /* 1688：关注商品 */
    function follow()
    {
        global $_W;
        global $_GPC;

        $productId = $_GPC['offer_id'];
        $category_ids = $_GPC['category_ids'];
        $formula = $_GPC['price'];
        if (empty($category_ids)) {
            show_json(0, "商品分类不能为空");
        }

        $code_arr = array(
            'productId' => $productId,//商品ID
            'access_token' => $this->access_token//请求token
        );

        $apiInfo = 'param2/1/com.alibaba.product/alibaba.product.follow/' . $this->appkey;

        if (file_get_contents($this->get_alibaba_api($this->appkey, $this->appSecret, $apiInfo, $code_arr))) {

            $code_arr = array(
                'offerId' => $productId,//商品ID
                'access_token' => $this->access_token//请求token
            );

            $apiInfo = 'param2/1/com.alibaba.product/alibaba.cpsMedia.productInfo/' . $this->appkey;
            $detail = file_get_contents($this->get_alibaba_api($this->appkey, $this->appSecret, $apiInfo, $code_arr));
            $detail = json_decode($detail, true);

            if ($detail['errorCode'] == '500_1') {
                show_json(0, $detail['errorMsg']);
            }

            $price = $detail['productInfo']['saleInfo']['consignPrice']? eval(str_replace("n",$detail['productInfo']['saleInfo']['consignPrice'],$formula)) : 0;
            $goods = pdo_get('ewei_shop_goods', array('uniacid' => $_W['uniacid'], 'productID' => $detail['productInfo']['productID']));

            /* 基本商品信息 */
            $data = [];
            $data['uniacid'] = $_W['uniacid'];
            $data['type'] = 1;  //类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡
            $data['status'] = 0; //	状态 0 下架 1 上架 2 赠品上架
            $data['displayorder'] = 0;  //排序
            $data['title'] = $detail['productInfo']['subject'];  //标题
            $data['thumb'] = $detail['productInfo']['image']['images'][0];  //图片
            $data['video'] = $detail['productInfo']['mainVedio'];//单次最低购买
            $data['unit'] = $detail['productInfo']['saleInfo']['unit'];  //排序
            $data['content'] = $detail['productInfo']['description'];  //商品详情
            $data['goodssn'] = "https://detail.1688.com/offer/" . $productId . ".html";  //1688路径
            $data['productprice'] = $detail['productInfo']['saleInfo']['retailprice']; //原价
            $data['marketprice'] = eval("return ".str_replace("n",$detail['productInfo']['saleInfo']['consignPrice'],$formula).";");  //建议零售价
            $data['costprice'] = $detail['productInfo']['saleInfo']['consignPrice'];  //成本价
            $data['originalprice'] = 0;  //排序
            $data['total'] = $detail['productInfo']['saleInfo']['amountOnSale'];  //库存
            $data['sales'] = 0;//已出售数
            $data['thumb_url'] = serialize($detail['productInfo']['image']['images']);//详情图
            $data['minbuy'] = serialize($detail['productInfo']['saleInfo']['minOrderQuantity']);//单次最低购买
            $data['createtime'] = time();//创建时间
            $data['Weight'] = $detail['productInfo']['shippingInfo']['unitWeight'];  //重量/毛重
            $data['thumb_url'] = serialize($detail['productInfo']['image']['images']);//图片轮播
            $data['shorttitle'] = $detail['productInfo']['subject'];//段标题
            $data['ccates'] = $category_ids;//分类
            $data['cates'] = $category_ids;//分类
            $data['minprice'] = $price;//最小金额
            $data['maxprice'] = $price;//最大金额
            $data['province'] = explode(" ", $detail['productInfo']['shippingInfo']['sendGoodsAddressText'])[0]; //省份
            $data['city'] = explode(" ", $detail['productInfo']['shippingInfo']['sendGoodsAddressText'])[1];//城市
            $data['subtitle'] = $detail['productInfo']['subject'];  //副标题
            $data['productID'] = $detail['productInfo']['productID'];  //1688商品ID
            $data['goods_type'] = 3;  //商品类型
            $data['thumb_first'] = 1;  //商详情显示首图



            if ($goods) {
                $result = pdo_update('ewei_shop_goods', $data, array('uniacid' => $_W['uniacid'], 'productID' => $detail['productInfo']['productID']));
                $id = $goods['id'];
            } else {
                $result = pdo_insert('ewei_shop_goods', $data);
                $id = pdo_insertid();
            }


            /* 商品参数信息 */
            $attributes = $detail['productInfo']['attributes'];

            foreach($attributes as $value){
                $data = [];
                $data['uniacid'] = $_W['uniacid'];
                $data['goodsid'] = $id;
                $data['title'] = $value['attributeName'];
                $data['value'] = $value['value'];
                $data['attributeID'] = $value['attributeID'];

                $param = pdo_get('ewei_shop_goods_param',array('uniacid'=>$_W['uniacid'],'attributeID'=>$value['attributeID']));

                if(empty($param)){
                    $result = pdo_insert('ewei_shop_goods_param',$data);
                }else{
                    $result = pdo_update('ewei_shop_goods_param',$data,array('uniacid'=>$_W['uniacid'],'attributeID'=>$value['attributeID']));
                }

            }



            /* 多规格信息 */
            $data = [];
            $skuInfos = $detail['productInfo']['skuInfos'];

            if ($skuInfos) {
                $result = pdo_update('ewei_shop_goods', array('hasoption' => '1'), array('uniacid' => $_W['uniacid'], 'productID' => $detail['productInfo']['productID']));

            }

            foreach ($skuInfos as $value) {
                //查询数组库是否有1688的规格ID
                foreach ($value['attributes'] as $v) {
                    $spec = pdo_get('ewei_shop_goods_spec', array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'attributeID' => $v['attributeID']));


                    //如果没有就删除旧数据然后重新插入
                    if (empty($spec)) {
                        $data = [];
                        $data['uniacid'] = $_W['uniacid'];//公众号ID
                        $data['goodsid'] = $id;//商品ID
                        $data['title'] = $v['attributeName'];//规格标题
                        $data['attributeID'] = $v['attributeID'];//规格标题

                        // 插入规格信息
                        $result = pdo_insert('ewei_shop_goods_spec', $data);
                    } else {
                        $data = [];
                        $data['uniacid'] = $_W['uniacid'];//公众号ID
                        $data['goodsid'] = $id;//商品ID
                        $data['title'] = $v['attributeName'];//规格标题
                        //修改规格信息
                        $result = pdo_update('ewei_shop_goods_spec', $data, array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'attributeID' => $v['attributeID']));

                    }
                }
            };

            foreach ($skuInfos as $value) {


                foreach ($value['attributes'] as $v) {
                    $spec = pdo_get('ewei_shop_goods_spec', array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'attributeID' => $v['attributeID']));
                    $spec_item = pdo_get('ewei_shop_goods_spec_item', array('uniacid' => $_W['uniacid'], 'specid' => $spec['id'], 'attributeValue' => $v['attributeValue']));
                    $data = [];
                    $data['uniacid'] = $_W['uniacid'];//公众号ID
                    $data['title'] = $v['attributeValue'];
                    $data['specid'] = $spec['id'];
                    if ($v['skuImageUrl'])
                        $data['thumb'] = $v['skuImageUrl'];
                    $data['show'] = 1;
                    $data['attributeValue'] = $v['attributeValue'];



                    if (empty($spec_item)) {
                        $result = pdo_insert('ewei_shop_goods_spec_item', $data);
                    }else{
                        $result = pdo_update('ewei_shop_goods_spec_item',$data,array('uniacid'=>$_W['uniacid'],'specid'=>$spec['id'],'id'=>$spec_item['id']));

                    }
                }
            }


            foreach ($skuInfos as $value) {

                $where = '';
                $option_title = '';
                $option_item_id = '';


                foreach ($value['attributes'] as $key => $v) {
                    $spec = pdo_get('ewei_shop_goods_spec', array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'attributeID' => $v['attributeID']));
                    $spec_item = pdo_get('ewei_shop_goods_spec_item', array('uniacid' => $_W['uniacid'], 'specid' => $spec['id'], 'attributeValue' => $v['attributeValue']));
                    $item_id = $spec_item['id'];

                    if($key == 0){
                        $option_title .= $v['attributeValue'];
                        $option_item_id .= $item_id;
                    }
                    if($key != 0){
                        $option_title .= '+'.$v['attributeValue'];
                        $option_item_id .= '_'.$item_id;
                    }

                }

//                $where .= ' and specs like "%'.$item_id.'%" ';
                $where .= ' and specs = "'.$option_item_id.'" ';
                $option = pdo_fetch('SELECT id,title FROM ' . tablename('ewei_shop_goods_option') . ' WHERE 1 '.$where.' and uniacid=' . $_W['uniacid']);

                $data = [];
                $data['goodsid'] = $id;
                $data['title'] = $option_title;
                $data['uniacid'] = $_W['uniacid'];
                $data['skuId'] = $value['skuId'];
                $data['specid'] = $value['specId'];
                $data['stock'] = $value['amountOnSale'];
                $data['marketprice'] = eval("return ".str_replace("n",$value['consignPrice'],$formula).";");
                $data['costprice'] = $value['consignPrice'];
                $data['thumb'] = $value['skuImageUrl'];
                $data['specs'] = $option_item_id;
                $data['goodssn'] = "https://detail.1688.com/offer/".$productId.".html";


                if($option){
                    $result = pdo_update('ewei_shop_goods_option',$data,array('uniacid'=>$_W['uniacid'],'goodsid'=>$id,'id'=>$option['id']));
                }else{
                    $result = pdo_insert('ewei_shop_goods_option', $data);
                }



            }
            show_json(1, "关注成功");
        } else {
            show_json(0, "关注失败");
        }


    }

    function get_alibaba_api($appkey, $appSecret, $apiInfo, $code_arr)
    {
        $url = 'http://gw.api.alibaba.com/openapi/';//1688开放平台使用gw.open.1688.com域名

        $aliParams = array();
        foreach ($code_arr as $key => $val) {
            $aliParams[] = $key . $val;
        }

        sort($aliParams);
        $sign_str = join('', $aliParams);
        $sign_str = $apiInfo . $sign_str;



        $code_sign = strtoupper(bin2hex(hash_hmac("sha1", $sign_str, $appSecret, true)));


        $aliParams = array();
        foreach ($code_arr as $key => $val) {
            $aliParams[] = $key . '=' . $val;
        }
        $sign_str = join('&', $aliParams);
        $sign_str = $apiInfo . '?' . $sign_str;

        return $url . $sign_str . "&_aop_signature=" . $code_sign;
    }


}

?>
