/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2013-03-28 10:52:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `cz_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `cz_admin_user`;
CREATE TABLE `cz_admin_user` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `reg_time` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `last_ip` varchar(20) DEFAULT NULL,
  `question` int(11) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cz_admin_user
-- ----------------------------
INSERT INTO `cz_admin_user` VALUES ('1', 'admin', 'admin', 'admin@shop.com', '', '1', '1363951374', '211.68.43.9', '0', '');
INSERT INTO `cz_admin_user` VALUES ('2', 'productor', 'productor', 'productor@shop.com', '', '1', '2', '211.68.43.9', '0', '');

-- ----------------------------
-- Table structure for `cz_category`
-- ----------------------------
DROP TABLE IF EXISTS `cz_category`;
CREATE TABLE `cz_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL COMMENT '父级分类的ID',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cz_category
-- ----------------------------
INSERT INTO `cz_category` VALUES ('1', '充值卡', '50', '0');
INSERT INTO `cz_category` VALUES ('2', '手机配件', '50', '0');
INSERT INTO `cz_category` VALUES ('3', '手机类型', '50', '0');
INSERT INTO `cz_category` VALUES ('4', '移动充值', '50', '1');
INSERT INTO `cz_category` VALUES ('5', '联通充值', '50', '1');
INSERT INTO `cz_category` VALUES ('6', '电信充值', '50', '1');
INSERT INTO `cz_category` VALUES ('7', '充电器', '50', '2');
INSERT INTO `cz_category` VALUES ('8', '电池', '50', '2');
INSERT INTO `cz_category` VALUES ('9', '数据线', '30', '2');
INSERT INTO `cz_category` VALUES ('10', '直板手机', '50', '3');
INSERT INTO `cz_category` VALUES ('11', '翻盖手机', '50', '3');
INSERT INTO `cz_category` VALUES ('12', 'UDB数据线', '50', '9');
INSERT INTO `cz_category` VALUES ('13', 'micro-USB数据线', '50', '9');
INSERT INTO `cz_category` VALUES ('14', '耳机', '30', '2');

-- ----------------------------
-- Table structure for `cz_goods`
-- ----------------------------
DROP TABLE IF EXISTS `cz_goods`;
CREATE TABLE `cz_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(100) DEFAULT NULL COMMENT '商品名称',
  `goods_sn` varchar(20) DEFAULT '' COMMENT '货号',
  `goods_price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `goods_number` int(11) DEFAULT NULL COMMENT '库存',
  `category_id` int(11) DEFAULT NULL COMMENT '分类id指向category表的主键',
  `is_on_sale` int(11) DEFAULT '1' COMMENT '是否上架,1为上架,0为下架',
  `goods_desc` text COMMENT '商品信息简介,描述',
  `click_count` int(11) DEFAULT NULL COMMENT '点击量',
  `goods_img` varchar(255) DEFAULT NULL COMMENT '商品图片,原始图片',
  `goods_thumb` varchar(255) DEFAULT NULL COMMENT '商品图片缩略图,保存路径',
  `update_time` int(11) DEFAULT NULL COMMENT '最后更新时间',
  `is_best` int(11) DEFAULT NULL COMMENT '是否为精品,1为精品,0为非精品',
  `is_hot` int(11) DEFAULT NULL COMMENT '是否为热销',
  `is_new` int(11) DEFAULT NULL COMMENT '是否为新品',
  `is_promote` int(11) DEFAULT NULL COMMENT '是否推荐',
  `is_special` int(11) DEFAULT NULL COMMENT '是否为特价',
  `special_price` decimal(10,2) DEFAULT NULL COMMENT '特价时的价格',
  `sort_order` int(11) DEFAULT NULL COMMENT '排序',
  `is_delete` int(11) DEFAULT '0' COMMENT '1表示商品被删除,0表示商品未被删除',
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cz_goods
-- ----------------------------
INSERT INTO `cz_goods` VALUES ('1', 'iphone3', 'CZ0001', '3058.02', '100', '12', '1', '很好的手机！', '10', '', '', '1234', '0', '0', '0', '1', '1', '1058.02', '50', '0');
INSERT INTO `cz_goods` VALUES ('2', 'iphone4', 'CZ0002', '4058.02', '100', '12', '1', '更好的手机！', '10', '', '', '1234', '0', '0', '0', '1', '0', '0.00', '50', '0');
INSERT INTO `cz_goods` VALUES ('3', 'iphone5', 'CZ0003', '5058.02', '100', '12', '1', '最好的手机！', '10', '', '', '1234', '1', '1', '1', '1', '0', '0.00', '50', '0');
INSERT INTO `cz_goods` VALUES ('4', '诺基亚N9', 'CZ0010', '3035.02', '100', '12', '1', '这个手机有点好！', '10', '', '', '1234', '0', '1', '0', '1', '1', '234.02', '50', '0');
INSERT INTO `cz_goods` VALUES ('5', '诺基亚3230', 'CZ0012', '58.02', '100', '12', '1', '手机怎么样，你怎么看？', '10', '', '', '1234', '1', '0', '0', '1', '0', '1058.02', '50', '0');

-- ----------------------------
-- Table structure for `cz_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `cz_sessions`;
CREATE TABLE `cz_sessions` (
  `sess_id` varchar(50) NOT NULL,
  `sess_data` text,
  `expire` int(11) DEFAULT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cz_sessions
-- ----------------------------
INSERT INTO `cz_sessions` VALUES ('1jtimgbcrnkpehhfpej687pq44', 'captcha_code|s:4:\"72gm\";', '1363951387');
INSERT INTO `cz_sessions` VALUES ('rrd7d1n4kioaqovbrvlgtobsi6', 'captcha_code|s:4:\"ytPS\";admin|a:10:{s:8:\"admin_id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:8:\"password\";s:5:\"admin\";s:5:\"email\";s:14:\"admin@shop.com\";s:6:\"action\";s:0:\"\";s:8:\"reg_time\";s:1:\"1\";s:10:\"last_login\";s:10:\"1363665822\";s:7:\"last_ip\";s:11:\"211.68.43.9\";s:8:\"question\";s:1:\"0\";s:6:\"answer\";s:0:\"\";}', '1363669312');
