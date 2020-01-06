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

class AppError
{

    public static $OK = 0; //成功
    public static $SystemError = -1; //系统内部错误
    public static $ParamsError = -2; //参数错误
    public static $UserNotLogin = -3; //未登录
    public static $VerifyFailed = -10; //验证失败
    public static $PluginNotFound = -9; // 插件未找到
    public static $RequestError = -6; // 错误的请求

    public static $VerifyCodeError = 90000; //验证码错误
    public static $VerifyCodeTimeOut = 90001; //验证码过期

    public static $SMSTplidNull = 91000; //短信模板为空
    public static $SMSRateError = 91001; //短信发送频率过快

    public static $BindSelfBinded = 92000; //此手机号已与当前账号绑定
    public static $BindWillRelieve = 92001; //此手机号已与其他帐号绑定, 如果继续将会解绑之前帐号
    public static $BindWillMerge = 92002; //此手机号已通过其他方式注册, 如果继续将会合并账号信息
    public static $BindError = 92003; //绑定失败
    public static $BindNotOpen = 92004; //未开启绑定
    public static $BindConfirm = 92005; //绑定确认


    public static $UploadNoFile = 101000; //无文件上传
    public static $UploadFail = 101001; //上传失败


    public static $UserLoginFail = 10000; //登录失败
    public static $UserTokenFail = 10001; //登录失效
    public static $UserMobileExists = 10002; //手机号已存在
    public static $UserNotFound = 10003; //用户不存在
    public static $UserIsBlack = 10004; //用户是黑名单
    public static $UserNotBindMobile = 10010; //用户未绑定手机号


    //public static $ShopClosed  = 20000;//商城关闭

    public static $GoodsNotFound = 20000;//商品不存在
    public static $GoodsNotChecked = 20001;//商品未审核
    public static $NotAddCart = 20002;//不能加入购物车
    public static $NotInCart = 20003;//无购物车记录

    public static $AddressNotFound = 30000;//地址未找到

    public static $WithdrawNotOpen = 30101; //系统未开启提现
    public static $WithdrawError = 30102; //提现金额错误
    public static $WithdrawBig = 30103; //提现金额过大
    public static $WithdrawNotType = 30104; //未选择提现方式
    public static $WithdrawRealName = 30105; //请填写姓名
    public static $WithdrawAlipay = 30106; //请填写支付宝帐号
    public static $WithdrawAlipay1 = 30107; //请填写确认帐号
    public static $WithdrawDiffAlipay = 30108; //支付宝帐号与确认帐号不一致
    public static $WithdrawBank = 30109; //请选择银行
    public static $WithdrawBankCard = 30110; //请填写银行卡号
    public static $WithdrawBankCard1 = 30111; //请填写确认卡号
    public static $WithdrawDiffBankCard = 30112; //银行卡号与确认卡号不一致


    public static $WxPayNotOpen = 40000; // 微信支付未开启
    public static $WxPayParamsError = 40001; // 微信支付参数错误


    public static $OrderNotFound = 50000;//订单未找到
    public static $OrderNoExpress = 50001; //无物流信息
    public static $OrderCannotCancel = 50002; //订单不能取消
    public static $OrderCannotFinish = 50003; //订单不能收货
    public static $OrderCannotRestore = 50004; //订单不能恢复
    public static $OrderCannotDelete = 50005; //订单不能删除
    public static $OrderCreateNoGoods = 50006; //无商品
    public static $OrderCreateMinBuyLimit = 50007; //最低购买限制
    public static $OrderCreateOneBuyLimit = 50008; //一次最多购买限制
    public static $OrderCreateMaxBuyLimit = 50009; //最多购买限制
    public static $OrderCreateTimeNotStart = 50010; //限时购时间未开始
    public static $OrderCreateTimeEnd = 50011; //限时购时间已结束
    public static $OrderCreateMemberLevelLimit = 50012; //会员等级限制
    public static $OrderCreateMemberGroupLimit = 50013; //会员组限制
    public static $OrderCreateStockError = 50014; //库存不足

    public static $OrderCannotPay = 50015; //订单不能支付
    public static $OrderPayNoPayType = 50016; //没有合适的支付方式
    public static $OrderPayFail = 50017; //支付出错
    public static $OrderAlreadyPay = 50018; //订单已经支付
    public static $OrderCanNotResubmit  = 50019; //请不要重复提交
    public static $OrderCanNotRefund = 51000; //订单不能申请退款
    public static $OrderCanNotComment = 51001; //订单不能申请评论

    public static $OrderCreateTaskGoodsCart = 50202; //任务活动优惠商品不能放入购物车下单
    public static $OrderCreateNoDispatch = 50203; //不配送区域
    public static $OrderCreateFalse = 50204; //下单失败
    public static $OrderCreateNoPackage = 50205; //未找到套餐
    public static $OrderCreatePackageTimeNotStart = 50206; //套餐未开始
    public static $OrderCreatePackageTimeEnd = 50207; //套餐已结束

    public static $MemberRechargeError = 60000; //会员充值错误

    public static $CouponNotFound = 61000;     // 优惠券不存在
    public static $CouponCanNotBuy = 61001;     // 无法从领券中心领取
    public static $CouponRecordNotFound = 61002;     // 未找到优惠券领取记录
    public static $CouponBuyError = 61003;     // 优惠券领取失败

    public static $CommissionReg = 70000; //跳转到注册页面
    public static $CommissionNoUserInfo = 70001; //需要您完善资料才能继续操作
    public static $CommissionNotShortTimeSubmit = 70002; //不要短时间重复下提交
    public static $CommissionIsAgent = 70003; //您已经是分销商了
    public static $CommissionQrcodeNoOpen = 70004; //没有开启推广二维码!
    public static $CommissionPosterNotFound = 70005; // 未找到分销海报
    public static $PosterCreateFail = 70006; // 海报生成失败

    // 店铺装修 80开头
    public static $PageNotFound = 80000;    //页面不存在
    public static $MenuNotFound = 80001;    //自定义菜单不存在

    // 手机端商家管理中心 81开头
    public static $PermError = 81000;   // 无权操作
    public static $ManageNotOpen = 81001;   // 未开启
    public static $RefundFail = 81002;   // 退款失败
    public static $PluginNotOpen = 81003;   // 插件未开启

    //积分商城   82开头
    public static $OrderNndone = 82001;
    public static $RecordNotFound = 82002;
    public static $logNotFound = 82003;
    public static $NoExchangeAuthority = 82004; //没有兑换权限
    public static $ExchangeRecordNotFound = 82005;
    public static $RecordUsed = 82006;
    public static $BeyondUsefulLife = 82007;
    public static $NonsupportOfflineConversion = 82008;
    public static $Losing_Lottery = 82009;
    public static $NonPayment = 82010;
    public static $NonPaymentFreight = 82011;
    public static $Expire = 82012; //超出有效期
    public static $GoodsSoldOut = 82013; //商品下架
    public static $BrowseAuthority = 82014; //商品未找到
    public static $GoodsOptionNotFound = 82015; //
    public static $PacketGet = 82016; //
    public static $PacketDissatisfyCondition = 82017; //
    public static $MoneyInsufficient = 82018; //商
    public static $PacketError = 82019; //
    public static $OrderNotTake = 82020; //
    public static $CanBuy = 82021; //
    public static $NotFoundAddress = 82022; //
    public static $NotOpenWPay = 82023; //

    public static $AuthEnticationFail =  82024; //砍价身份验证失败

    //团队分红
    public static  $CommissionIsNotAgent = 82025; //不是分销商
    public static  $DividendAgent = 82026; //已经是团长

    //会员卡
    public static $CardNotFund = 82030;     //会员卡不存在
    public static $CardisStop = 82031;      //已经停止发卡
    public static $CardisDel = 82032;       //会员卡已经被删除
    public static $CardisOverTime = 82040;       //购买的会员卡已过期
    public static $NotGetCard = 82041;       //还未开通会员卡

    public static $WxAppError = 9900001;
    public static $WxAppLoginError =9900002;

    public static $errCode = array(
        '0' => '处理成功',
        '-1' => '系统内部错误',
        '-2' => '参数错误',
        '-3'=>'未登录',
        '-9'=>'插件未找到',
        '-6'=>'错误的请求',

        '90000' => '验证码错误',
        '90001' => '验证码失效',

        '10000' => '登录失败',
        '10001' => '登录失效',
        '10002' => '手机号已存在',
        '10003' => '用户不存在',
        '10004' => '用户是黑名单',
        
        '10010' => '用户未绑定手机号',

        //'20000' =>'商城关闭',
        '20000' => '商品不存在',
        '20001' => '商品不存在(1)',
        '20002' => '不能加入购物车',
        '20003' => '无购物车记录',

        '30000' => '地址未找到',
        '30101' => '系统未开启提现',
        '30102' => '提现金额错误',
        '30103' => '提现金额过大',
        '30104' => '未选择提现方式',
        '30105' => '请填写姓名',
        '30106' => '请填写支付宝帐号',
        '30107' => '请填写确认帐号',
        '30108' => '支付宝帐号与确认帐号不一致',
        '30109' => '请选择银行',
        '30110' => '请填写银行卡号',
        '30111' => '请填写确认卡号',
        '30112' => '银行卡号与确认卡号不一致',


        '40000' => '微信支付未开启',
        '40001' => '微信支付参数错误',


        '80000' => '页面不存在',
        '80001' => '菜单不存在',

        '81000' => '无权限操作',
        '81001' => '未开启管理端',
        '81002' => '退款失败',
        '81003' => '插件未开启',

        '91000' => '短信发送失败(SMSidNull)',
        '91001' => '60秒内只能发送一次',

        '92000' => '此手机号已与当前账号绑定',
        '92001' => '此手机号已与其他帐号绑定, 如果继续将会解绑之前帐号',
        '92002' => '此手机号已通过其他方式注册, 如果继续将会合并账号信息',
        '92003' => '绑定失败',
        '92004' => '未开启绑定',
        '92005' => '绑定确认',


        '101000' => '未选择文件',
        '101001' => '上传失败',

        '50000' => '订单未找到',
        '50001' => '无物流信息',
        '50002' => '订单无法取消',
        '50003' => '订单无法收货',
        '50004' => '订单无法恢复',
        '50005' => '订单无法删除',
        '50006' => '商品出错',

        '50007' => '最低购买限制',
        '50008' => '一次最多购买限制',
        '50009' => '最多购买限制',
        '50010' => '限时购时间未开始',
        '50011' => '限时购时间已结束',
        '50012' => '会员等级限制',
        '50013' => '会员组限制',
        '50014' => '库存不足',
        '50015' => '订单不能支付',
        '50016' =>'没有合适的支付方式',
        '50017' =>'支付出错',
        '50018' =>'订单已经支付',
        '50019' =>'请不要重复提交',
        '51000' =>'订单不能申请退款',
        '51001' => '订单不能评论',

        '50201' => '任务活动优惠最多购买限制',
        '50202' => '任务活动优惠商品不能放入购物车下单',
        '50203' => '不配送区域',
        '50204' => '下单失败',
        '50205' => '未找到套餐',
        '50206' => '套餐未开始',
        '50207' => '套餐已结束',

		'61000' => '优惠券不存在',
        '61001' => '无法从领券中心领取',
        '61002' => '未找到优惠券领取记录',
        '61003' => '优惠券领取失败',

		'70000' => '跳转到注册页面',
        '70001' => '需要您完善资料才能继续操作',
        '70002' => '不要短时间重复下提交',
        '70003' => '您已经是分销商了',
        '70004' => '没有开启推广二维码!',
        '70005' => '未找到分销海报!',
        '70006' => '海报生成失败',

        '82024' => '身份验证失败',

        '82025' => '您还不是分销商',
        '82026' => '您已经是团长',

        '82030' => '会员卡不存在',
        '82031' => '已经停止发卡',
        '82032' => '会员卡已经被删除',
        '82040' => '购买的会员卡已过期',
        '82041' => '还未开通会员卡',
    );

    static function getError($errcode = 0)
    {
        return isset(self::$errCode[$errcode]) ? self::$errCode[$errcode] : '';
    }

}
