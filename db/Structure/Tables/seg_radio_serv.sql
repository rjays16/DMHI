
DROP TABLE IF EXISTS `seg_radio_serv`;
CREATE TABLE `seg_radio_serv` (
  `refno` varchar(12) NOT NULL,
  `request_date` date NOT NULL,
  `request_time` time NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `discount` decimal(10,8) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `ordername` varchar(200) DEFAULT NULL,
  `orderaddress` varchar(300) DEFAULT NULL,
  `is_cash` tinyint(1) NOT NULL,
  `type_charge` int(11) DEFAULT NULL,
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `is_tpl` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this approved by billing for charge?',
  `comments` varchar(200) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `is_pay_full` tinyint(1) DEFAULT '0',
  `walkin_pid` varchar(12) DEFAULT NULL,
  `source_req` varchar(10) DEFAULT NULL,
  `area_type` varchar(10) DEFAULT NULL,
  `grant_type` varchar(10) DEFAULT NULL,
  `is_pe` tinyint(1) DEFAULT '0',
  `is_rdu` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_radio_serv` (`pid`),
  KEY `FK_seg_radio_serv_service` (`area_type`),
  KEY `FK_seg_radio_serv_source` (`source_req`),
  KEY `status_index` (`status`),
  KEY `rdate_index` (`request_date`),
  KEY `rtime_index` (`request_time`),
  KEY `encounternr_index` (`encounter_nr`),
  KEY `ordername_index` (`ordername`),
  CONSTRAINT `FK_seg_radio_serv_service` FOREIGN KEY (`area_type`) REFERENCES `seg_service_area` (`area_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_radio_serv_source` FOREIGN KEY (`source_req`) REFERENCES `seg_type_request_source` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
