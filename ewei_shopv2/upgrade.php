<?php
pdo_query("
DROP TABLE IF EXISTS `ims_ewei_shop_exhelper_esheet`;
CREATE TABLE `ims_ewei_shop_exhelper_esheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(50) DEFAULT '',
  `code` varchar(20) NOT NULL DEFAULT '',
  `datas` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_ewei_shop_exhelper_esheet
-- ----------------------------
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('1', '顺丰', 'shunfeng', 'SF', 'a:2:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}i:1;a:4:{s:5:\"style\";s:9:\"三联210\";s:4:\"spec\";s:38:\"（宽100mm 高210mm 切点90/60/60）\";s:4:\"size\";s:3:\"210\";s:9:\"isdefault\";i:0;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('2', '百世快递', 'huitongkuaidi', 'HTKY', 'a:2:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:0;}i:1;a:4:{s:5:\"style\";s:9:\"二联183\";s:4:\"spec\";s:37:\"（宽100mm 高183mm 切点87/5/91）\";s:4:\"size\";s:3:\"183\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('3', '韵达', 'yunda', 'YD', 'a:2:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:0;}i:1;a:4:{s:5:\"style\";s:9:\"二联203\";s:4:\"spec\";s:36:\"（宽100mm 高203mm 切点152/51）\";s:4:\"size\";s:3:\"203\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('4', '申通', 'shentong', 'STO', 'a:2:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}i:1;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:35:\"（宽100mm 高150mm 切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:0;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('5', '圆通', 'yuantong', 'YTO', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('6', 'EMS', 'ems', 'EMS', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('7', '中通', 'zhongtong', 'ZTO', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('8', '德邦', 'debangwuliu', 'DBL', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联177\";s:4:\"spec\";s:34:\"（宽100mm高177mm切点107/70）\";s:4:\"size\";s:3:\"177\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('9', '优速', 'youshuwuliu', 'UC', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('10', '宅急送', 'zhaijisong', 'ZJS', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联120\";s:4:\"spec\";s:33:\"（宽100mm高116mm切点98/10）\";s:4:\"size\";s:3:\"120\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('11', '京东', 'jd', 'JD', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联110\";s:4:\"spec\";s:33:\"（宽100mm高110mm切点60/50）\";s:4:\"size\";s:3:\"110\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('12', '信丰', 'xinfengwuliu', 'XFEX', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('13', '全峰', 'quanfengkuaidi', 'QFKD', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('14', '跨越速运', 'kuayue', 'KYSY', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联137\";s:4:\"spec\";s:34:\"（宽100mm高137mm切点101/36）\";s:4:\"size\";s:3:\"137\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('15', '安能', 'annengwuliu', 'ANE', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"三联180\";s:4:\"spec\";s:37:\"（宽100mm高180mm切点110/30/40）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('16', '快捷', 'kuaijiesudi', 'FAST', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('17', '国通', 'guotongkuaidi', 'GTO', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('18', '天天', 'tiantian', 'HHTT', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('19', '中铁快运', 'zhongtiekuaiyun', 'ZTKY', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('20', '邮政快递包裹', 'youzhengguonei', 'YZPY', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联180\";s:4:\"spec\";s:34:\"（宽100mm高180mm切点110/70）\";s:4:\"size\";s:3:\"180\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('21', '邮政国内标快', 'youzhengguonei', 'YZBK', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('22', '全一快递', 'quanyikuaidi', 'UAPEX', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:32:\"（宽90mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');
INSERT INTO `ims_ewei_shop_exhelper_esheet` VALUES ('23', '速尔快递', 'sue', 'SURE', 'a:1:{i:0;a:4:{s:5:\"style\";s:9:\"二联150\";s:4:\"spec\";s:33:\"（宽100mm高150mm切点90/60）\";s:4:\"size\";s:3:\"150\";s:9:\"isdefault\";i:1;}}');

-- ----------------------------
-- Table structure for `ims_ewei_shop_express`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_express`;
CREATE TABLE `ims_ewei_shop_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '',
  `express` varchar(50) DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `displayorder` tinyint(3) unsigned DEFAULT '0',
  `code` varchar(30) NOT NULL DEFAULT '',
  `coding` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_ewei_shop_express
-- ----------------------------
INSERT INTO `ims_ewei_shop_express` VALUES ('1', '顺丰', 'shunfeng', '1', '0', 'JH_014', 'SF');
INSERT INTO `ims_ewei_shop_express` VALUES ('2', '申通', 'shentong', '1', '0', 'JH_005', 'STO');
INSERT INTO `ims_ewei_shop_express` VALUES ('3', '韵达快运', 'yunda', '1', '0', 'JH_003', 'YD');
INSERT INTO `ims_ewei_shop_express` VALUES ('4', '天天快递', 'tiantian', '1', '0', 'JH_004', 'HHTT');
INSERT INTO `ims_ewei_shop_express` VALUES ('5', '圆通速递', 'yuantong', '1', '0', 'JH_002', 'YTO');
INSERT INTO `ims_ewei_shop_express` VALUES ('6', '中通速递', 'zhongtong', '1', '0', 'JH_006', 'ZTO');
INSERT INTO `ims_ewei_shop_express` VALUES ('7', 'ems快递', 'ems', '1', '0', 'JH_001', 'EMS');
INSERT INTO `ims_ewei_shop_express` VALUES ('8', '百世快递', 'huitongkuaidi', '1', '0', 'JH_012', 'HTKY');
INSERT INTO `ims_ewei_shop_express` VALUES ('9', '全峰快递', 'quanfengkuaidi', '1', '0', 'JH_009', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('10', '宅急送', 'zhaijisong', '1', '0', 'JH_007', 'ZJS');
INSERT INTO `ims_ewei_shop_express` VALUES ('11', 'aae全球专递', 'aae', '1', '0', 'JHI_049', 'AAE');
INSERT INTO `ims_ewei_shop_express` VALUES ('12', '安捷快递', 'anjie', '1', '0', '', 'AJ');
INSERT INTO `ims_ewei_shop_express` VALUES ('13', '安信达快递', 'anxindakuaixi', '1', '0', 'JH_131', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('14', '彪记快递', 'biaojikuaidi', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('15', 'bht', 'bht', '1', '0', 'JHI_008', 'BHT');
INSERT INTO `ims_ewei_shop_express` VALUES ('16', '百福东方国际物流', 'baifudongfang', '1', '0', 'JH_062', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('17', '中国东方（COE）', 'coe', '1', '0', 'JHI_038', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('18', '长宇物流', 'changyuwuliu', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('19', '大田物流', 'datianwuliu', '1', '0', 'JH_050', 'DTWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('20', '德邦物流', 'debangwuliu', '1', '0', 'JH_011', 'DBL');
INSERT INTO `ims_ewei_shop_express` VALUES ('21', 'dhl', 'dhl', '1', '0', 'JHI_002', 'DHL');
INSERT INTO `ims_ewei_shop_express` VALUES ('22', 'dpex', 'dpex', '1', '0', 'JHI_011', 'DPEX');
INSERT INTO `ims_ewei_shop_express` VALUES ('23', 'd速快递', 'dsukuaidi', '1', '0', 'JH_049', 'DSWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('24', '递四方', 'disifang', '1', '0', 'JHI_080', 'D4PX');
INSERT INTO `ims_ewei_shop_express` VALUES ('25', 'fedex（国外）', 'fedex', '1', '0', 'JHI_014', 'FEDEX_GJ');
INSERT INTO `ims_ewei_shop_express` VALUES ('26', '飞康达物流', 'feikangda', '1', '0', 'JH_088', 'FKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('27', '凤凰快递', 'fenghuangkuaidi', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('28', '飞快达', 'feikuaida', '1', '0', 'JH_151', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('29', '国通快递', 'guotongkuaidi', '1', '0', 'JH_010', 'GTO');
INSERT INTO `ims_ewei_shop_express` VALUES ('30', '港中能达物流', 'ganzhongnengda', '1', '0', 'JH_033', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('31', '广东邮政物流', 'guangdongyouzhengwuliu', '1', '0', 'JH_135', 'GDEMS');
INSERT INTO `ims_ewei_shop_express` VALUES ('32', '共速达', 'gongsuda', '1', '0', 'JH_039', 'GSD');
INSERT INTO `ims_ewei_shop_express` VALUES ('33', '恒路物流', 'hengluwuliu', '1', '0', 'JH_048', 'HLWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('34', '华夏龙物流', 'huaxialongwuliu', '1', '0', 'JH_129', 'HXLWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('35', '海红', 'haihongwangsong', '1', '0', 'JH_132', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('36', '海外环球', 'haiwaihuanqiu', '1', '0', 'JHI_013', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('37', '佳怡物流', 'jiayiwuliu', '1', '0', 'JH_035', 'JYWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('38', '京广速递', 'jinguangsudikuaijian', '1', '0', 'JH_041', 'JGSD');
INSERT INTO `ims_ewei_shop_express` VALUES ('39', '急先达', 'jixianda', '1', '0', 'JH_040', 'JXD');
INSERT INTO `ims_ewei_shop_express` VALUES ('40', '佳吉物流', 'jiajiwuliu', '1', '0', 'JH_030', 'CNEX');
INSERT INTO `ims_ewei_shop_express` VALUES ('41', '加运美物流', 'jymwl', '1', '0', 'JH_054', 'JYM');
INSERT INTO `ims_ewei_shop_express` VALUES ('42', '金大物流', 'jindawuliu', '1', '0', 'JH_079', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('43', '嘉里大通', 'jialidatong', '1', '0', 'JH_060', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('44', '晋越快递', 'jykd', '1', '0', 'JHI_046', 'JYKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('45', '快捷速递', 'kuaijiesudi', '1', '0', 'JH_008', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('46', '联邦快递（国内）', 'lianb', '1', '0', 'JH_122', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('47', '联昊通物流', 'lianhaowuliu', '1', '0', 'JH_021', 'LHT');
INSERT INTO `ims_ewei_shop_express` VALUES ('48', '龙邦物流', 'longbanwuliu', '1', '0', 'JH_019', 'LB');
INSERT INTO `ims_ewei_shop_express` VALUES ('49', '立即送', 'lijisong', '1', '0', 'JH_044', 'LJSKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('50', '乐捷递', 'lejiedi', '1', '0', 'JH_043', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('51', '民航快递', 'minghangkuaidi', '1', '0', 'JH_100', 'MHKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('52', '美国快递', 'meiguokuaidi', '1', '0', 'JHI_044', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('53', '门对门', 'menduimen', '1', '0', 'JH_036', 'MDM');
INSERT INTO `ims_ewei_shop_express` VALUES ('54', 'OCS', 'ocs', '1', '0', 'JHI_012', 'OCS');
INSERT INTO `ims_ewei_shop_express` VALUES ('55', '配思货运', 'peisihuoyunkuaidi', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('56', '全晨快递', 'quanchenkuaidi', '1', '0', 'JH_055', 'QCKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('57', '全际通物流', 'quanjitong', '1', '0', 'JH_127', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('58', '全日通快递', 'quanritongkuaidi', '1', '0', 'JH_029', 'QRT');
INSERT INTO `ims_ewei_shop_express` VALUES ('59', '全一快递', 'quanyikuaidi', '1', '0', 'JH_020', 'UAPEX');
INSERT INTO `ims_ewei_shop_express` VALUES ('60', '如风达', 'rufengda', '1', '0', 'JH_017', 'RFD');
INSERT INTO `ims_ewei_shop_express` VALUES ('61', '三态速递', 'santaisudi', '1', '0', 'JH_065', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('62', '盛辉物流', 'shenghuiwuliu', '1', '0', 'JH_066', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('63', '速尔物流', 'suer', '1', '0', 'JH_016', 'SURE');
INSERT INTO `ims_ewei_shop_express` VALUES ('64', '盛丰物流', 'shengfeng', '1', '0', 'JH_082', 'SFWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('65', '赛澳递', 'saiaodi', '1', '0', 'JH_042', 'SAD');
INSERT INTO `ims_ewei_shop_express` VALUES ('66', '天地华宇', 'tiandihuayu', '1', '0', 'JH_018', 'HOAU');
INSERT INTO `ims_ewei_shop_express` VALUES ('67', 'tnt', 'tnt', '1', '0', 'JHI_003', 'TNT');
INSERT INTO `ims_ewei_shop_express` VALUES ('68', 'ups', 'ups', '1', '0', 'JHI_004', 'UPS');
INSERT INTO `ims_ewei_shop_express` VALUES ('69', '万家物流', 'wanjiawuliu', '1', '0', '', 'WJWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('70', '文捷航空速递', 'wenjiesudi', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('71', '伍圆', 'wuyuan', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('72', '万象物流', 'wxwl', '1', '0', 'JH_115', 'WXWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('73', '新邦物流', 'xinbangwuliu', '1', '0', 'JH_022', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('74', '信丰物流', 'xinfengwuliu', '1', '0', 'JH_023', 'XFEX');
INSERT INTO `ims_ewei_shop_express` VALUES ('75', '亚风速递', 'yafengsudi', '1', '0', 'JH_075', 'YFSD');
INSERT INTO `ims_ewei_shop_express` VALUES ('76', '一邦速递', 'yibangwuliu', '1', '0', 'JH_064', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('77', '优速物流', 'youshuwuliu', '1', '0', 'JH_013', 'UC');
INSERT INTO `ims_ewei_shop_express` VALUES ('78', '邮政快递包裹', 'youzhengguonei', '1', '0', 'JH_077', 'YZPY');
INSERT INTO `ims_ewei_shop_express` VALUES ('79', '邮政国际包裹挂号信', 'youzhengguoji', '1', '0', '', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('80', '远成物流', 'yuanchengwuliu', '1', '0', 'JH_024', 'YCWL');
INSERT INTO `ims_ewei_shop_express` VALUES ('81', '源伟丰快递', 'yuanweifeng', '1', '0', 'JH_141', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('82', '元智捷诚快递', 'yuanzhijiecheng', '1', '0', 'JH_126', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('83', '运通快递', 'yuntongkuaidi', '1', '0', 'JH_145', 'YTKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('84', '越丰物流', 'yuefengwuliu', '1', '0', 'JH_068', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('85', '源安达', 'yad', '1', '0', 'JH_067', 'YADEX');
INSERT INTO `ims_ewei_shop_express` VALUES ('86', '银捷速递', 'yinjiesudi', '1', '0', 'JH_148', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('87', '中铁快运', 'zhongtiekuaiyun', '1', '0', 'JH_015', 'ZTKY');
INSERT INTO `ims_ewei_shop_express` VALUES ('88', '中邮物流', 'zhongyouwuliu', '1', '0', 'JH_027', 'ZYKD');
INSERT INTO `ims_ewei_shop_express` VALUES ('89', '忠信达', 'zhongxinda', '1', '0', 'JH_086', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('90', '芝麻开门', 'zhimakaimen', '1', '0', 'JH_026', '');
INSERT INTO `ims_ewei_shop_express` VALUES ('91', '安能物流', 'annengwuliu', '1', '0', 'JH_059', 'ANE');
INSERT INTO `ims_ewei_shop_express` VALUES ('92', '京东快递', 'jd', '1', '0', 'JH_046', 'JD');
INSERT INTO `ims_ewei_shop_express` VALUES ('93', '微特派', 'weitepai', '1', '0', '', 'WTP');
INSERT INTO `ims_ewei_shop_express` VALUES ('94', '九曳供应链', 'jiuyescm', '1', '0', '', 'JIUYE');

-- ----------------------------
-- Table structure for `ims_ewei_shop_member_message_template_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_member_message_template_type`;
CREATE TABLE `ims_ewei_shop_member_message_template_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `typecode` varchar(255) DEFAULT NULL,
  `templatecode` varchar(255) DEFAULT NULL,
  `templateid` varchar(255) DEFAULT NULL,
  `templatename` varchar(255) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `typegroup` varchar(255) DEFAULT '',
  `groupname` varchar(255) DEFAULT '',
  `showtotaladd` tinyint(1) DEFAULT '0',
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of ims_ewei_shop_member_message_template_type
-- ----------------------------
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('1', '订单付款通知', 'saler_pay', 'OPENTM405584202', '', '订单付款通知', '{{first.DATA}}订单编号：{{keyword1.DATA}}商品名称：{{keyword2.DATA}}商品数量：{{keyword3.DATA}}支付金额：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('2', '自提订单提交成功通知', 'carrier', 'OPENTM201594720', '', '订单付款通知', '{{first.DATA}}自提码：{{keyword1.DATA}}商品详情：{{keyword2.DATA}}提货地址：{{keyword3.DATA}}提货时间：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('3', '订单取消通知', 'cancel', 'OPENTM201764653', '', '订单关闭提醒', '{{first.DATA}}订单商品：{{keyword1.DATA}}订单编号：{{keyword2.DATA}}下单时间：{{keyword3.DATA}}订单金额：{{keyword4.DATA}}关闭时间：{{keyword5.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('4', '订单即将取消通知', 'willcancel', 'OPENTM201764653', '', '订单关闭提醒', '{{first.DATA}}订单商品：{{keyword1.DATA}}订单编号：{{keyword2.DATA}}下单时间：{{keyword3.DATA}}订单金额：{{keyword4.DATA}}关闭时间：{{keyword5.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('5', '订单支付成功通知', 'pay', 'OPENTM405584202', '', '订单支付通知', '{{first.DATA}}订单编号：{{keyword1.DATA}}商品名称：{{keyword2.DATA}}商品数量：{{keyword3.DATA}}支付金额：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('6', '订单发货通知', 'send', 'OPENTM401874827', '', '订单发货通知', '{{first.DATA}}订单编号：{{keyword1.DATA}}快递公司：{{keyword2.DATA}}快递单号：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('7', '自动发货通知(虚拟物品及卡密)', 'virtualsend', 'OPENTM207793687', '', '自动发货通知', '{{first.DATA}}商品名称：{{keyword1.DATA}}订单号：{{keyword2.DATA}}订单金额：{{keyword3.DATA}}卡密信息：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('8', '订单状态更新(修改收货地址)(修改价格)', 'orderstatus', 'TM00017', '', '订单付款通知', '{{first.DATA}}订单编号:{{OrderSn.DATA}}订单状态:{{OrderStatus.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('9', '退款成功通知', 'refund1', 'TM00430', '', '退款成功通知', '{{first.DATA}}退款金额：{{orderProductPrice.DATA}}商品详情：{{orderProductName.DATA}}订单编号：{{orderName.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('10', '换货成功通知', 'refund3', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('11', '退款申请驳回通知', 'refund2', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('12', '充值成功通知', 'recharge_ok', 'OPENTM207727673', '', '充值成功提醒', '{{first.DATA}}充值金额：{{keyword1.DATA}}充值时间：{{keyword2.DATA}}账户余额：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('13', '提现成功通知', 'withdraw_ok', 'OPENTM207422808', '', '提现通知', '{{first.DATA}}申请提现金额：{{keyword1.DATA}}取提现手续费：{{keyword2.DATA}}实际到账金额：{{keyword3.DATA}}提现渠道：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('14', '会员升级通知(任务处理通知)', 'upgrade', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('15', '充值成功通知（后台管理员手动）', 'backrecharge_ok', 'OPENTM207727673', '', '充值成功提醒', '{{first.DATA}}充值金额：{{keyword1.DATA}}充值时间：{{keyword2.DATA}}账户余额：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('16', '积分变动提醒', 'backpoint_ok', 'OPENTM207509450', '', '积分变动提醒', '{{first.DATA}}获得时间：{{keyword1.DATA}}获得积分：{{keyword2.DATA}}获得原因：{{keyword3.DATA}}当前积分：{{keyword4.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('17', '换货发货通知', 'refund4', 'OPENTM401874827', '', '订单发货通知', '{{first.DATA}}订单编号：{{keyword1.DATA}}快递公司：{{keyword2.DATA}}快递单号：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('18', '砍价活动通知', 'bargain_message', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'bargain', '砍价消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('19', '拼团活动通知', 'groups', null, null, null, null, 'groups', '拼团消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('20', '人人分销通知', 'commission', null, null, null, null, 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('21', '商品付款通知', 'saler_goodpay', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('22', '砍到底价通知', 'bargain_fprice', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'bargain', '砍价消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('23', '订单收货通知(卖家)', 'saler_finish', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('24', '余额兑换成功通知', 'exchange_balance', 'OPENTM207727673', '', '充值成功提醒', '{{first.DATA}}充值金额：{{keyword1.DATA}}充值时间：{{keyword2.DATA}}账户余额：{{keyword3.DATA}}{{remark.DATA}}', 'exchange', '兑换中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('25', '积分兑换成功通知', 'exchange_score', 'OPENTM207509450', '', '积分变动提醒', '{{first.DATA}}获得时间：{{keyword1.DATA}}获得积分：{{keyword2.DATA}}获得原因：{{keyword3.DATA}}当前积分：{{keyword4.DATA}}{{remark.DATA}}', 'exchange', '兑换中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('26', '兑换中心余额充值通知', 'exchange_recharge', 'OPENTM207727673', '', '充值成功提醒', '{{first.DATA}}充值金额：{{keyword1.DATA}}充值时间：{{keyword2.DATA}}账户余额：{{keyword3.DATA}}{{remark.DATA}}', 'exchange', '兑换中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('27', '游戏中心通知', 'lottery_get', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'lottery', '抽奖消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('35', '库存预警通知', 'saler_stockwarn', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('36', '卖家核销商品核销通知', 'o2o_sverify', 'OPENTM409521536', '', '核销成功提醒', '{{first.DATA}}核销项目：{{keyword1.DATA}}核销时间：{{keyword2.DATA}}核销门店：{{keyword3.DATA}}{{remark.DATA}}', 'o2o', 'O2O消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('37', '核销商品核销通知', 'o2o_bverify', 'OPENTM409521536', '', '核销成功提醒', '{{first.DATA}}核销项目：{{keyword1.DATA}}核销时间：{{keyword2.DATA}}核销门店：{{keyword3.DATA}}{{remark.DATA}}', 'o2o', 'O2O消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('38', '卖家商品预约通知', 'o2o_snorder', 'OPENTM202447657', '', '预约成功提醒', '{{first.DATA}}预约项目：{{keyword1.DATA}}预约时间：{{keyword2.DATA}}{{remark.DATA}}', 'o2o', 'O2O消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('39', '商品预约成功通知', 'o2o_bnorder', 'OPENTM202447657', '', '预约成功提醒', '{{first.DATA}}预约项目：{{keyword1.DATA}}预约时间：{{keyword2.DATA}}{{remark.DATA}}', 'o2o', 'O2O消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('42', '商品下单通知', 'saler_goodsubmit', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('50', '维权订单通知', 'saler_refund', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sys', '系统消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('43', '任务接取通知', 'task_pick', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('44', '任务进度通知', 'task_progress', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('45', '任务完成通知', 'task_finish', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('46', '任务海报接取通知', 'task_poster_pick', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('47', '任务海报进度通知', 'task_poster_progress', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('48', '任务海报完成通知', 'task_poster_finish', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('49', '任务海报扫描通知', 'task_poster_scan', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'task', '任务中心消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('52', '成为分销商通知', 'commission_become', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('53', '新增下线通知', 'commission_agent_new', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('54', '下级付款通知', 'commission_order_pay', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('55', '下级确认收货通知', 'commission_order_finish', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('56', '提现申请提交通知', 'commission_apply', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('57', '提现申请完成审核通知', 'commission_check', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('58', '佣金打款通知', 'commission_pay', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('59', '分销商等级升级通知', 'commission_upgrade', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('60', '成为股东通知', 'globonus_become', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'globonus', '股东消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('61', '股东等级升级通知', 'globonus_upgrade', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'globonus', '股东消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('62', '分红发放通知', 'globonus_pay', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'globonus', '股东消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('63', '奖励发放通知', 'article', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'article', '文章营销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('64', '成为区域代理通知', 'abonus_become', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'abonus', '区域代理消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('65', '省级代理等级升级通知', 'abonus_upgrade1', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'abonus', '区域代理消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('66', '市级代理等级升级通知', 'abonus_upgrade2', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'abonus', '区域代理消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('67', '区级代理等级升级通知', 'abonus_upgrade3', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'abonus', '区域代理消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('68', '区域代理分红发放通知', 'abonus_pay', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'abonus', '区域代理消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('69', '入驻申请通知', 'merch_apply', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'merch', '商家通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('70', '提现申请提交通知', 'merch_applymoney', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'merch', '商家通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('71', '社区会员评论通知', 'reply', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sns', '人人社区消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('51', '社区会员升级通知', 'sns', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'sns', '人人社区消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('74', '周期购定时发货通知', 'cycelbuy_timing', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'cycelbuy', '周期购消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('73', '修改收货时间卖家通知', 'cycelbuy_seller_date', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'cycelbuy', '周期购消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('72', '修改地址卖家通知', 'cycelbuy_seller_address', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'cycelbuy', '周期购消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('75', '修改收货时间买家通知', 'cycelbuy_buyer_date', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'cycelbuy', '周期购消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('76', '修改地址买家通知', 'cycelbuy_buyer_address', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'cycelbuy', '周期购消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('77', '分销提现申请提醒', 'commission_applymoney', 'OPENTM207574677', null, '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'commission', '分销消息通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('80', '成为团长通知', 'dividend_become', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'dividend', '团队分红通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('81', '成为团长通知(卖家)', 'dividend_become_saler', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'dividend', '团队分红通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('82', '团员成为团长通知', 'dividend_downline_become', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'dividend', '团队分红通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('83', '团长提现通知', 'dividend_apply', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'dividend', '团队分红通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('84', '提现审核完成通知', 'dividend_check', 'OPENTM207574677', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}业务状态：{{keyword2.DATA}}业务内容：{{keyword3.DATA}}{{remark.DATA}}', 'dividend', '团队分红通知', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('85', '好友瓜分券活动发起通知', 'friendcoupon_launch', 'OPENTM415477060', '', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}处理状态：{{keyword2.DATA}}处理内容：{{keyword3.DATA}}{{remark.DATA}}', 'friendcoupon', '好友瓜分券', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('86', '好友瓜分券活动完成通知', 'friendcoupon_complete', 'OPENTM415477060', ' ', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}处理状态：{{keyword2.DATA}}处理内容：{{keyword3.DATA}}{{remark.DATA}}', 'friendcoupon', '好友瓜分券', '0');
INSERT INTO `ims_ewei_shop_member_message_template_type` VALUES ('87', '好友瓜分券活动失败通知', 'friendcoupon_failed', 'OPENTM415477060', ' ', '业务处理通知', '{{first.DATA}}业务类型：{{keyword1.DATA}}处理状态：{{keyword2.DATA}}处理内容：{{keyword3.DATA}}{{remark.DATA}}', 'friendcoupon', '好友瓜分券', '0');

-- ----------------------------
-- Table structure for `ims_ewei_shop_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_plugin`;
CREATE TABLE `ims_ewei_shop_plugin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `displayorder` int(11) DEFAULT '0',
  `identity` varchar(50) DEFAULT '',
  `category` varchar(255) DEFAULT '',
  `name` varchar(50) DEFAULT '',
  `version` varchar(10) DEFAULT '',
  `author` varchar(20) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `desc` text,
  `iscom` tinyint(3) DEFAULT '0',
  `deprecated` tinyint(3) DEFAULT '0',
  `isv2` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_identity` (`identity`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_ewei_shop_plugin
-- ----------------------------
INSERT INTO `ims_ewei_shop_plugin` VALUES ('1', '1', 'qiniu', 'tool', '七牛存储', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/qiniu.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('2', '2', 'taobao', 'tool', '商品助手', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/taobao.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('3', '3', 'commission', 'biz', '人人分销', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/commission.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('4', '4', 'poster', 'sale', '超级海报', '1.2', '官方', '1', '../addons/ewei_shopv2/static/images/poster.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('5', '5', 'verify', 'biz', 'O2O核销', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/verify.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('6', '6', 'tmessage', 'tool', '会员群发', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/tmessage.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('7', '7', 'perm', 'help', '分权系统', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/perm.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('8', '8', 'sale', 'sale', '营销宝', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/sale.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('9', '9', 'designer', 'help', '店铺装修V1', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/designer.jpg', null, '0', '1', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('10', '10', 'creditshop', 'biz', '积分商城', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/creditshop.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('11', '11', 'virtual', 'biz', '虚拟物品', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/virtual.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('12', '11', 'article', 'help', '文章营销', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/article.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('13', '13', 'coupon', 'sale', '超级券', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/coupon.jpg', null, '1', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('14', '14', 'postera', 'sale', '活动海报', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/postera.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('15', '16', 'system', 'help', '系统工具', '1.0', '官方', '0', '../addons/ewei_shopv2/static/images/system.jpg', null, '0', '1', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('16', '15', 'diyform', 'help', '自定表单', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/diyform.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('17', '16', 'exhelper', 'help', '快递助手', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/exhelper.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('18', '19', 'groups', 'biz', '人人拼团', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/groups.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('19', '20', 'diypage', 'help', '店铺装修', '2.0', '官方', '1', '../addons/ewei_shopv2/static/images/designer.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('20', '22', 'globonus', 'biz', '全民股东', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/globonus.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('21', '23', 'merch', 'biz', '多商户', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/merch.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('22', '26', 'qa', 'help', '帮助中心', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/qa.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('23', '27', 'sms', 'tool', '短信提醒', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/sms.jpg', '', '1', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('24', '29', 'sign', 'tool', '积分签到', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/sign.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('25', '30', 'sns', 'sale', '全民社区', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/sns.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('26', '33', 'wap', 'tool', '全网通', '1.0', '官方', '1', '', '', '1', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('27', '34', 'h5app', 'tool', 'H5APP', '1.0', '官方', '1', '', '', '1', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('28', '26', 'abonus', 'biz', '区域代理', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/abonus.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('29', '33', 'printer', 'tool', '小票打印机', '1.0', '官方', '1', '', '', '1', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('30', '34', 'bargain', 'tool', '砍价活动', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/bargain.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('31', '35', 'task', 'sale', '任务中心', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/task.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('32', '36', 'cashier', 'biz', '收银台', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/cashier.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('33', '37', 'messages', 'tool', '消息群发', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/messages.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('34', '38', 'seckill', 'sale', '整点秒杀', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/seckill.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('35', '39', 'exchange', 'biz', '兑换中心', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/exchange.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('36', '65', 'wxcard', 'sale', '微信卡券', '1.0', '官方', '1', '', null, '1', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('37', '42', 'quick', 'biz', '快速购买', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/quick.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('38', '43', 'mmanage', 'tool', '手机端商家管理中心', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/mmanage.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('39', '44', 'pc', 'tool', 'PC端', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/pc.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('40', '45', 'lottery', 'biz', '游戏营销', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/lottery.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('41', '46', 'live', 'sale', '互动直播', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/live.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('42', '47', 'invitation', 'sale', '邀请卡', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/invitation.png', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('43', '48', 'app', 'help', '小程序', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/app.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('44', '49', 'merchmanage', 'tool', '多商户手机端', '2.0', '官方', '1', '../addons/ewei_shopv2/static/images/merchmanage.jpg', '', '0', '0', '0');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('45', '50', 'cycelbuy', 'biz', '周期购', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/cycelbuy.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('46', '51', 'polyapi', 'tool', '网店管家', '1.0', '二开', '1', '../addons/ewei_shopv2/static/images/polyapi.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('47', '52', 'dividend', 'biz', '团队分红', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/dividend.jpg', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('48', '53', 'membercard', 'sale', '付费会员卡', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/membercard.png', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('49', '54', 'friendcoupon', 'sale', '好友瓜分券', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/friendcoupon.png', '', '0', '0', '1');
INSERT INTO `ims_ewei_shop_plugin` VALUES ('50', '55', 'universalform', 'tool', '报名调研', '1.0', '官方', '1', '../addons/ewei_shopv2/static/images/universalform.jpg', '', '0', '0', '1');

-- ----------------------------
-- Table structure for `ims_ewei_shop_task_type`
-- ----------------------------
DROP TABLE IF EXISTS `ims_ewei_shop_task_type`;
CREATE TABLE `ims_ewei_shop_task_type` (
  `id` int(11) NOT NULL,
  `type_key` char(20) NOT NULL DEFAULT '',
  `type_name` char(10) NOT NULL DEFAULT '',
  `description` char(30) NOT NULL DEFAULT '',
  `verb` char(11) NOT NULL DEFAULT '',
  `numeric` tinyint(1) NOT NULL DEFAULT '0',
  `unit` char(10) NOT NULL DEFAULT '',
  `goods` tinyint(1) NOT NULL DEFAULT '0',
  `theme` char(10) NOT NULL DEFAULT '',
  `once` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_ewei_shop_task_type
-- ----------------------------
INSERT INTO `ims_ewei_shop_task_type` VALUES ('1', 'poster', '任务海报', '把生成的海报并分享给朋友，朋友扫描并关注公众号即可获得奖励。', '转发海报并吸引', '1', '人关注', '0', 'primary', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('2', 'info_phone', '绑定手机', '在个人中心中，绑定手机号，即可完成任务获得奖励。', '绑定手机', '0', '', '0', 'warning', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('3', 'order_first', '首次购物', '在商城中首次下单，即可获得奖励，必须确认收货。', '首次在商城中下单购物', '0', '', '0', 'warning', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('4', 'recharge_full', '单笔充值满额', '在商城中充值余额，单笔充值满额，即可获得奖励。', '单笔充值满', '1', '元', '0', 'success', '1');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('5', 'order_full', '单笔满额', '在商城中下单，单笔满额即可获得奖励，必须确认收货。', '单笔订单满', '1', '元', '0', 'success', '1');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('6', 'order_all', '累计消费', '在商城中购物消费，累计满额即可获得奖励，无需确认收货。', '购物总消费额达到', '1', '元', '0', 'success', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('7', 'pyramid_money', '分销佣金', '只有分销商可接此任务。累计分销佣金满额，即可完成任务。', '分销商获得佣金金额达', '1', '元', '0', 'primary', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('8', 'pyramid_num', '下级人数', '只有分销商可接此任务。累计下级人数达标，即可完成任务。', '分销商推荐下级人数达', '1', '人', '0', 'primary', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('9', 'comment', '商品好评', '任意给一个商品五星好评，即可完成任务获得奖励。', '给商品好评', '0', '', '0', 'warning', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('10', 'post', '社区发帖', '在社区中发表指定篇帖子，即可完成任务获得奖励。', '在论坛中发表', '1', '篇帖子', '0', 'warning', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('11', 'goods', '购买指定商品', '购买指定商品后即可完成任务，必须确认收货。', '购买指定商品', '0', '', '1', 'info', '0');
INSERT INTO `ims_ewei_shop_task_type` VALUES ('12', 'recharge_count', '累计充值满额', '在商城中充值余额，累计充值满额，即可获得奖励。', '累计充值满', '1', '元', '0', 'success', '0');
");
