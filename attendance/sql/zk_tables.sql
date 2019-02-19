-- MySQL dump 10.13  Distrib 5.6.22-72.0, for Linux (x86_64)
--
-- Host: localhost    Database: zk
-- ------------------------------------------------------
-- Server version	5.6.22-72.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anomaly`
--

DROP TABLE IF EXISTS `anomaly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anomaly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwDate` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  `reason` varchar(200) DEFAULT NULL,
  `isack` int(10) DEFAULT '0',
  `type` int(10) NOT NULL DEFAULT '0',
  `stime` varchar(32) DEFAULT NULL,
  `etime` varchar(32) DEFAULT NULL,
  `addtime` varchar(64) DEFAULT NULL,
  `type_sub` varchar(64) DEFAULT NULL,
  `sumtime` float DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_dwDate` (`dwDate`(20)),
  KEY `index_Name` (`Name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=13910 DEFAULT CHARSET=utf8 COMMENT='考勤异常说明表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `anomaly_sta`
--

DROP TABLE IF EXISTS `anomaly_sta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anomaly_sta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwDate` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(200) DEFAULT NULL,
  `isack` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_dwDate` (`dwDate`(20)),
  KEY `index_phoneNum` (`phoneNum`(32))
) ENGINE=InnoDB AUTO_INCREMENT=414 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwEnrollNumber` varchar(200) DEFAULT NULL,
  `dwVerifyMode` varchar(200) DEFAULT NULL,
  `dwInOutMode` varchar(200) DEFAULT NULL,
  `dwDate` varchar(200) DEFAULT NULL,
  `dwTime` varchar(200) DEFAULT NULL,
  `dwWorkCode` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `depname` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_dwDate` (`dwDate`(20)),
  KEY `index_Name` (`Name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=147509 DEFAULT CHARSET=utf8 COMMENT='指纹打卡表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attendance_copy`
--

DROP TABLE IF EXISTS `attendance_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwEnrollNumber` varchar(200) DEFAULT NULL,
  `dwVerifyMode` varchar(200) DEFAULT NULL,
  `dwInOutMode` varchar(200) DEFAULT NULL,
  `dwDate` varchar(200) DEFAULT NULL,
  `dwTime` varchar(200) DEFAULT NULL,
  `dwWorkCode` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `depname` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_dwDate` (`dwDate`(20)),
  KEY `index_Name` (`Name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='指纹打卡表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bglog`
--

DROP TABLE IF EXISTS `bglog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bglog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phonenum` varchar(20) DEFAULT NULL,
  `opertime` varchar(64) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `realname` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2504 DEFAULT CHARSET=utf8 COMMENT='微信开道闸日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bglogNew`
--

DROP TABLE IF EXISTS `bglogNew`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bglogNew` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phoneNum` varchar(20) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `carLicense` varchar(20) DEFAULT NULL,
  `operTime` varchar(64) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56985 DEFAULT CHARSET=utf8 COMMENT='道闸通过日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bglog_QR`
--

DROP TABLE IF EXISTS `bglog_QR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bglog_QR` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phoneNum` varchar(20) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `uuid` varchar(128) DEFAULT NULL,
  `operTime` varchar(64) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=402 DEFAULT CHARSET=utf8 COMMENT='二维码开道闸日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carlicense`
--

DROP TABLE IF EXISTS `carlicense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carlicense` (
  `uVehicleID` int(10) DEFAULT NULL COMMENT '车辆ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `uCustomerID` int(10) DEFAULT NULL COMMENT '用户ID',
  `strName` varchar(64) DEFAULT NULL COMMENT '用户名',
  `strCode` varchar(64) DEFAULT NULL COMMENT '用户编码',
  `bEnable` int(11) DEFAULT NULL COMMENT '是否允许',
  `bUsingTimeSeg` int(11) DEFAULT NULL COMMENT '是否匹配时间段',
  `struTMCreate` varchar(64) DEFAULT NULL COMMENT '创建时间',
  `struTMEnable` varchar(64) DEFAULT NULL COMMENT '生效时间',
  `struTMOverdule` varchar(64) DEFAULT NULL COMMENT '结束时间',
  `iColor` int(10) DEFAULT NULL COMMENT '车牌颜色',
  `iPlateType` int(10) DEFAULT NULL COMMENT '车牌类型',
  `iBlackList` int(11) DEFAULT NULL COMMENT '是否黑名单',
  `strCodeCar` varchar(64) DEFAULT NULL COMMENT '车辆编码',
  `strRemark` varchar(128) DEFAULT NULL COMMENT '车辆备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单车牌表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carlicense_copy`
--

DROP TABLE IF EXISTS `carlicense_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carlicense_copy` (
  `uVehicleID` int(10) DEFAULT NULL COMMENT '车辆ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `uCustomerID` int(10) DEFAULT NULL COMMENT '用户ID',
  `strName` varchar(64) DEFAULT NULL COMMENT '用户名',
  `strCode` varchar(64) DEFAULT NULL COMMENT '用户编码',
  `bEnable` int(11) DEFAULT NULL COMMENT '是否允许',
  `bUsingTimeSeg` int(11) DEFAULT NULL COMMENT '是否匹配时间段',
  `struTMCreate` varchar(64) DEFAULT NULL COMMENT '创建时间',
  `struTMEnable` varchar(64) DEFAULT NULL COMMENT '生效时间',
  `struTMOverdule` varchar(64) DEFAULT NULL COMMENT '结束时间',
  `iColor` int(10) DEFAULT NULL COMMENT '车牌颜色',
  `iPlateType` int(10) DEFAULT NULL COMMENT '车牌类型',
  `iBlackList` int(11) DEFAULT NULL COMMENT '是否黑名单',
  `strCodeCar` varchar(64) DEFAULT NULL COMMENT '车辆编码',
  `strRemark` varchar(128) DEFAULT NULL COMMENT '车辆备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单车牌表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carlicense_exp`
--

DROP TABLE IF EXISTS `carlicense_exp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carlicense_exp` (
  `uVehicleID` int(10) DEFAULT NULL COMMENT '车辆ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `uCustomerID` varchar(32) DEFAULT NULL COMMENT '用户ID',
  `strName` varchar(64) DEFAULT NULL COMMENT '用户名',
  `strCode` varchar(64) DEFAULT NULL COMMENT '用户编码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单导出导入表(修改此表)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carlicense_exp_copy`
--

DROP TABLE IF EXISTS `carlicense_exp_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carlicense_exp_copy` (
  `uVehicleID` int(10) DEFAULT NULL COMMENT '车辆ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `uCustomerID` varchar(32) DEFAULT NULL COMMENT '用户ID',
  `strName` varchar(64) DEFAULT NULL COMMENT '用户名',
  `strCode` varchar(64) DEFAULT NULL COMMENT '用户编码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单导出导入表(修改此表)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carlicense_exp_copy2`
--

DROP TABLE IF EXISTS `carlicense_exp_copy2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carlicense_exp_copy2` (
  `uVehicleID` int(10) DEFAULT NULL COMMENT '车辆ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `uCustomerID` varchar(32) DEFAULT NULL COMMENT '用户ID',
  `strName` varchar(64) DEFAULT NULL COMMENT '用户名',
  `strCode` varchar(64) DEFAULT NULL COMMENT '用户编码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='白名单导出导入表(修改此表)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gatelog`
--

DROP TABLE IF EXISTS `gatelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gatelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vID` varchar(20) DEFAULT NULL,
  `opertime` varchar(64) DEFAULT NULL,
  `doorID` varchar(64) DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COMMENT='微信开门表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT NULL,
  `logintime` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26132 DEFAULT CHARSET=utf8 COMMENT='考勤系统登陆日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menjin`
--

DROP TABLE IF EXISTS `menjin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menjin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indexId` varchar(200) DEFAULT NULL,
  `cardNum` varchar(200) DEFAULT NULL,
  `action` varchar(200) DEFAULT NULL,
  `doorId` int(11) DEFAULT '0',
  `doorSN` varchar(200) DEFAULT NULL,
  `swipeDate` varchar(200) DEFAULT NULL,
  `swipeTime` varchar(200) DEFAULT NULL,
  `reasonNo` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `depname` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_swipeDate` (`swipeDate`(20)),
  KEY `index_Name` (`Name`(20))
) ENGINE=InnoDB AUTO_INCREMENT=743656 DEFAULT CHARSET=utf8 COMMENT='门禁日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notice`
--

DROP TABLE IF EXISTS `notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anoID` int(11) NOT NULL DEFAULT '0',
  `username` varchar(200) DEFAULT NULL,
  `isack` int(10) DEFAULT '0',
  `isread` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `index_username` (`username`(20)),
  KEY `index_anoID` (`anoID`)
) ENGINE=InnoDB AUTO_INCREMENT=17324 DEFAULT CHARSET=utf8 COMMENT='异常领导证明人信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tmp`
--

DROP TABLE IF EXISTS `tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plate` varchar(20) DEFAULT NULL,
  `res` int(11) DEFAULT NULL,
  `time` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='微信开道闸日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tongji`
--

DROP TABLE IF EXISTS `tongji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tongji` (
  `Name` varchar(200) NOT NULL,
  `depname` varchar(200) NOT NULL,
  `workdays` varchar(200) NOT NULL,
  `wgdays` int(11) NOT NULL,
  `jbhours` varchar(200) NOT NULL,
  `jbdays` varchar(200) NOT NULL,
  `txhours` varchar(200) NOT NULL,
  `txdays` varchar(200) NOT NULL,
  `qjhours` varchar(200) NOT NULL,
  `qjdays` varchar(200) NOT NULL,
  `dwDate` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwEnrollNumber` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Privilege` varchar(200) DEFAULT NULL,
  `Enabled` varchar(200) DEFAULT NULL,
  `cardNum` varchar(200) DEFAULT NULL,
  `depname` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 COMMENT='用户信息表(指纹机和门禁系统)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `userinfo_tmp`
--

DROP TABLE IF EXISTS `userinfo_tmp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userinfo_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwEnrollNumber` varchar(200) DEFAULT NULL,
  `Name` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `Privilege` varchar(200) DEFAULT NULL,
  `Enabled` varchar(200) DEFAULT NULL,
  `cardNum` varchar(200) DEFAULT NULL,
  `depname` varchar(200) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `passwd` varchar(64) DEFAULT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `status` int(11) DEFAULT NULL,
  `realname` varchar(64) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  `mail` varchar(64) DEFAULT NULL,
  `carlicense` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 COMMENT='考勤系统登陆用户表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_copy`
--

DROP TABLE IF EXISTS `users_copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_copy` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '',
  `passwd` varchar(64) DEFAULT NULL,
  `groupid` int(10) unsigned NOT NULL,
  `status` int(11) DEFAULT NULL,
  `realname` varchar(64) DEFAULT NULL,
  `phoneNum` varchar(20) DEFAULT NULL,
  `mail` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitors` (
  `vID` int(10) DEFAULT NULL COMMENT '访客ID',
  `strPlateID` varchar(32) DEFAULT NULL COMMENT '车牌号码',
  `phoneNum` varchar(32) DEFAULT NULL COMMENT '手机号',
  `Name` varchar(64) DEFAULT NULL COMMENT '用户名',
  `bEnable` int(11) DEFAULT NULL COMMENT '是否允许',
  `TMCreate` varchar(64) DEFAULT NULL COMMENT '创建时间',
  `TMBegin` varchar(64) DEFAULT NULL COMMENT '生效时间',
  `TMEnd` varchar(64) DEFAULT NULL COMMENT '结束时间',
  `doorIDs` varchar(64) DEFAULT NULL COMMENT '门ID',
  `Type` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访客系统表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wr`
--

DROP TABLE IF EXISTS `wr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dwDate` varchar(200) DEFAULT NULL,
  `type` int(10) DEFAULT '0',
  `stime` varchar(40) DEFAULT NULL,
  `etime` varchar(40) DEFAULT NULL,
  `des` varchar(200) DEFAULT NULL,
  `stime_tx` int(10) DEFAULT NULL,
  `etime_tx` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_dwDate` (`dwDate`(20))
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COMMENT='作息时间表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-19 10:11:48
