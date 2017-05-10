/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : workerman_web_ape

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-10 10:22:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `a_agent_commodity`
-- ----------------------------
DROP TABLE IF EXISTS `a_agent_commodity`;
CREATE TABLE `a_agent_commodity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `point` int(11) DEFAULT '0' COMMENT '积分',
  `image` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `des` text,
  `admin_id` int(11) DEFAULT NULL,
  `buy_num` int(11) DEFAULT NULL,
  `click` int(11) DEFAULT '0',
  `is_show` int(11) DEFAULT '1' COMMENT '1正常 0下架',
  `short_str` varchar(2000) DEFAULT NULL COMMENT '段介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of a_agent_commodity
-- ----------------------------
INSERT INTO `a_agent_commodity` VALUES ('15', '2017-05-10 09:59:14', '2017-05-10 09:59:14', null, '2', 'upload/public/2017-05-10/1494381554_239.a', '1', '<p>3123123123123123<img src=\"/upload/ue/20170510/1494381552199936.png\" title=\"1494381552199936.png\" alt=\"scrawl.png\"/></p>', '1', '0', '0', '1', '12312');

-- ----------------------------
-- Table structure for `session`
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` varchar(191) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of session
-- ----------------------------
INSERT INTO `session` VALUES ('1494381462_3g31233gg', '2017-05-10 09:57:42', '2017-05-10 10:00:54', null, '{\"admin\":{\"id\":1,\"created_at\":\"2016-04-28 15:04:45\",\"updated_at\":\"2017-04-25 11:23:45\",\"deleted_at\":null,\"name\":\"admin\",\"phone\":\"admin\",\"password\":\"admin\",\"type\":1}}');

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '1总管理员',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('1', '2016-04-28 15:04:45', '2017-04-25 11:23:45', null, 'admin', 'admin', 'admin', '1');
