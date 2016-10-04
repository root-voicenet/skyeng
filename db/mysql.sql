/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50525
Source Host           : localhost:3306
Source Database       : real_db

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2016-10-05 05:03:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `question`
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `en` varchar(255) NOT NULL,
  `ru` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', 'apple', 'яблоко');
INSERT INTO `question` VALUES ('2', 'pear', 'персик');
INSERT INTO `question` VALUES ('3', 'orange', 'апельсин');
INSERT INTO `question` VALUES ('4', 'grape', 'виноград');
INSERT INTO `question` VALUES ('5', 'lemon', 'лимон');
INSERT INTO `question` VALUES ('6', 'pineapple', 'ананас');
INSERT INTO `question` VALUES ('7', 'watermelon', 'арбуз');
INSERT INTO `question` VALUES ('8', 'coconut', 'кокос');
INSERT INTO `question` VALUES ('9', 'banana', 'банан');
INSERT INTO `question` VALUES ('10', 'pomelo', 'помело');
INSERT INTO `question` VALUES ('11', 'strawberry', 'клубника');
INSERT INTO `question` VALUES ('12', 'raspberry', 'малина');
INSERT INTO `question` VALUES ('13', 'melon', 'дыня');
INSERT INTO `question` VALUES ('14', 'apricot', 'абрикос');
INSERT INTO `question` VALUES ('15', 'mango', 'манго');
INSERT INTO `question` VALUES ('16', 'pear', 'слива');
INSERT INTO `question` VALUES ('17', 'pomegranate', 'гранат');
INSERT INTO `question` VALUES ('18', 'cherry', 'вишня');

-- ----------------------------
-- Table structure for `quiz`
-- ----------------------------
DROP TABLE IF EXISTS `quiz`;
CREATE TABLE `quiz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `user_errors` tinyint(10) DEFAULT NULL,
  `user_rate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT NULL,
  `last_question` int(10) unsigned DEFAULT NULL,
  `created_at` bigint(10) unsigned DEFAULT NULL,
  `updated_at` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of quiz
-- ----------------------------
INSERT INTO `quiz` VALUES ('1', '2', null, '1', '0', '1', '1475599512', '1475609162');
INSERT INTO `quiz` VALUES ('2', '2', '3', '1', '0', '1', '1475611106', '1475611523');
INSERT INTO `quiz` VALUES ('3', '2', '3', '3', '0', '3', '1475611523', '1475612945');
INSERT INTO `quiz` VALUES ('4', '2', '9', '7', '0', '7', '1475612945', '1475613761');
INSERT INTO `quiz` VALUES ('5', '2', '3', '4', '20', '4', '1475613762', '1475613916');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `created_at` bigint(20) unsigned DEFAULT NULL,
  `updated_at` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'dsdsds', null, '10', '1475594815', '1475594815');
INSERT INTO `user` VALUES ('2', 'voicenet', 'ISWS47ZQd7zYU1eQOtEoI-3I6dRwAxQn', '10', '1475594966', '1475594966');

-- ----------------------------
-- Table structure for `wronganswer`
-- ----------------------------
DROP TABLE IF EXISTS `wronganswer`;
CREATE TABLE `wronganswer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(10) unsigned NOT NULL DEFAULT '0',
  `answer` varchar(255) NOT NULL,
  `direction` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wronganswer
-- ----------------------------
INSERT INTO `wronganswer` VALUES ('1', '1', 'банан', '0');
INSERT INTO `wronganswer` VALUES ('2', '5', 'арбуз', '0');
INSERT INTO `wronganswer` VALUES ('3', '5', 'малина', '0');
