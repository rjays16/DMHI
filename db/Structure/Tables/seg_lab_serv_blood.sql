
DROP TABLE IF EXISTS `seg_lab_serv_blood`;
CREATE TABLE `seg_lab_serv_blood` (
  `refno` varchar(12) NOT NULL,
  `serv_dt` date NOT NULL,
  `serv_tm` time NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `is_cash` tinyint(1) NOT NULL,
  `type_charge` int(11) DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `is_tpl` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this request approved by billing for charge?',
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `history` text,
  `comments` varchar(200) DEFAULT NULL,
  `ordername` varchar(200) DEFAULT NULL,
  `orderaddress` varchar(300) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `loc_code` varchar(10) DEFAULT NULL,
  `parent_refno` varchar(12) DEFAULT NULL,
  `approved_by_head` varchar(50) DEFAULT NULL,
  `remarks` text,
  `headID` varchar(35) DEFAULT NULL,
  `headpasswd` varchar(255) DEFAULT NULL,
  `discount` decimal(10,8) DEFAULT NULL,
  `fromBB` tinyint(1) DEFAULT '0',
  `walkin_pid` varchar(12) DEFAULT NULL,
  `source_req` varchar(10) DEFAULT NULL,
  `is_repeat` tinyint(1) DEFAULT '0',
  `is_rdu` tinyint(1) DEFAULT '0',
  `is_walkin` tinyint(1) DEFAULT '0',
  `is_pe` tinyint(1) DEFAULT '0',
  `area_type` varchar(10) DEFAULT NULL,
  `grant_type` varchar(10) DEFAULT NULL,
  `ref_source` enum('LB','BB','SPL','IC') DEFAULT NULL,
  PRIMARY KEY (`refno`),
  KEY `FK_seg_lab_serv` (`pid`),
  KEY `FK_seg_lab_serv_area` (`area_type`),
  KEY `FK_seg_lab_serv_source` (`source_req`),
  KEY `FK_seg_lab_serv_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_lab_serv_blood` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE NO ACTION ON UPDATE CASCADE
);