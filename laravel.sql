/*
Navicat MySQL Data Transfer

Source Server         : 192.168.18.50-Mysql5.7
Source Server Version : 50717
Source Host           : 192.168.18.50:3307
Source Database       : laravel

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-04-23 23:27:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `show_in_menu` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', 'System', '/admin/system', 'icon-cog', '1', '100', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `menu` VALUES ('2', '1', 'Menu', '/admin/system/menu', '', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `menu` VALUES ('3', '0', 'Dashboard', '/admin/dashboard', 'icon-home', '1', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `menu` VALUES ('4', '1', 'Settings', '/admin/system/configuration', '', '1', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `menu` VALUES ('5', '2', 'Menu Edit', '/admin/system/menu/edit/{id}', '', '0', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for `menu_role`
-- ----------------------------
DROP TABLE IF EXISTS `menu_role`;
CREATE TABLE `menu_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of menu_role
-- ----------------------------

-- ----------------------------
-- Table structure for `migrations`
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('2017_04_22_063309_menu', '1');
INSERT INTO `migrations` VALUES ('2017_04_22_063418_users', '2');
INSERT INTO `migrations` VALUES ('2017_04_22_064219_menu_role', '3');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nick_name` varchar(255) NOT NULL,
  `remember_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'zhongzhi.gu@commer.com', '$2y$10$82g.ywF6BPNcmlDgvOdbY..8BxZWGhkAzg70mmxNoZ08LUdsmj8SO', 'fdsa', 'XHmznqilMepwCm69oWrCitTrMTjWr8nOgveGCW0uJUMhJXHaZnoCKcU0tXp1', '2017-04-22 05:32:09', '2017-04-22 05:32:09');
