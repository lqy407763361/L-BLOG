/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : lblog

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2019-09-27 16:51:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lblog_about`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_about`;
CREATE TABLE `lblog_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `status` tinyint(4) NOT NULL,
  `edit_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_about
-- ----------------------------
INSERT INTO `lblog_about` VALUES ('1', '<p>这里是我的个人试验场</p>', '1', '1569513600');

-- ----------------------------
-- Table structure for `lblog_admin`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_admin`;
CREATE TABLE `lblog_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `add_time` bigint(20) NOT NULL,
  `edit_time` bigint(20) DEFAULT NULL,
  `add_ip` varchar(255) NOT NULL COMMENT '创建时IP',
  `last_login_ip` varchar(255) DEFAULT NULL COMMENT '最后登录时IP',
  `last_login_time` bigint(20) DEFAULT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_admin
-- ----------------------------
INSERT INTO `lblog_admin` VALUES ('1', '1', 'admin', 'SuperAdmin', '041eb96ddefd5688ed8a20d7152b11aca62cf50f', null, '1', '1569513600', '1569513600', '192.168.0.171', '192.168.0.171', '1569560627');

-- ----------------------------
-- Table structure for `lblog_admin_group`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_admin_group`;
CREATE TABLE `lblog_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` tinyint(4) NOT NULL,
  `view_power` text,
  `edit_power` text,
  `add_time` bigint(20) NOT NULL,
  `edit_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_admin_group
-- ----------------------------
INSERT INTO `lblog_admin_group` VALUES ('1', '超级管理员群组', '这是最高权限', '1', 'article,articleCategory,about,message,user,admin,adminGroup,siteConfig,siteLog', 'article,articleCategory,about,message,user,admin,adminGroup,siteConfig,siteLog', '1569513600', '1569513600');
INSERT INTO `lblog_admin_group` VALUES ('2', '游客', '只供查看的群组', '1', 'article,articleCategory,message,user,admin,adminGroup,siteConfig,siteLog', null, '1', null);

-- ----------------------------
-- Table structure for `lblog_article`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_article`;
CREATE TABLE `lblog_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `status` tinyint(4) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `add_time` bigint(20) NOT NULL,
  `edit_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_article
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_article_category`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_article_category`;
CREATE TABLE `lblog_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `add_time` bigint(20) NOT NULL,
  `edit_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_article_category
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_login_record`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_login_record`;
CREATE TABLE `lblog_login_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL,
  `login_ip` varchar(255) NOT NULL,
  `login_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_login_record
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_message`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_message`;
CREATE TABLE `lblog_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '是否阅读 1=已读 0=未读',
  `add_time` bigint(20) NOT NULL,
  `edit_time` bigint(20) DEFAULT NULL COMMENT '阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_message
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_register_type`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_register_type`;
CREATE TABLE `lblog_register_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `add_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_register_type
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_site_config`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_site_config`;
CREATE TABLE `lblog_site_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_title` varchar(255) NOT NULL,
  `meta_tag_description` varchar(255) DEFAULT NULL,
  `meta_tag_keywords` varchar(255) DEFAULT NULL,
  `qq` int(11) DEFAULT NULL,
  `wechat_image` varchar(255) DEFAULT NULL,
  `sinaweibo` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `site_login_max_number` tinyint(4) NOT NULL COMMENT '网站最大登陆次数',
  `admin_login_max_number` tinyint(4) NOT NULL COMMENT '后台最大登录次数',
  `site_config` text NOT NULL COMMENT '网站配置',
  `site_list_limit` tinyint(4) NOT NULL COMMENT '网页列表页最大数量',
  `admin_list_limit` tinyint(4) NOT NULL COMMENT '后台列表页最大数量',
  `email` varchar(255) NOT NULL COMMENT '邮箱地址',
  `smtp_host` varchar(255) NOT NULL COMMENT 'smtp主机',
  `smtp_username` varchar(255) NOT NULL COMMENT 'smtp用户名',
  `smtp_password` varchar(255) NOT NULL COMMENT 'smtp密码',
  `smtp_port` int(11) NOT NULL COMMENT 'smtp端口',
  `allow_upload_size` varchar(255) NOT NULL COMMENT '后台允许上传的文件大小',
  `allow_upload_type` varchar(255) NOT NULL COMMENT '后台允许上传的文件类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_site_config
-- ----------------------------
INSERT INTO `lblog_site_config` VALUES ('1', 'L-BLOG', 'L-BLOG', 'L-BLOG', '407763361', 'static/admin/upload/1565252421.jpeg', null, 'https://github.com/lqy407763361', '5', '5', '<p>底部配置</p>', '10', '20', '407763361@qq.com', '1', '1', '1', '1', '2', 'jpg,jpeg,png,zip');

-- ----------------------------
-- Table structure for `lblog_user`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_user`;
CREATE TABLE `lblog_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `register_type_id` tinyint(4) NOT NULL COMMENT '注册类型',
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `register_ip` varchar(255) NOT NULL,
  `next_login_ip` varchar(255) NOT NULL COMMENT '最后登录IP',
  `register_time` bigint(20) NOT NULL,
  `next_login_time` bigint(20) NOT NULL COMMENT '最后登录时间',
  `edit_time` bigint(20) DEFAULT NULL COMMENT '管理员对该账号操作时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_user
-- ----------------------------

-- ----------------------------
-- Table structure for `lblog_visit_record`
-- ----------------------------
DROP TABLE IF EXISTS `lblog_visit_record`;
CREATE TABLE `lblog_visit_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `visit_ip` varchar(255) NOT NULL,
  `add_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lblog_visit_record
-- ----------------------------
INSERT INTO `lblog_visit_record` VALUES ('1', '0', '192.168.0.171', '1569557027');
