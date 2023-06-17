
DROP TABLE IF EXISTS `care_test_request_radio`;
CREATE TABLE `care_test_request_radio` (
  `batch_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `refno` varchar(12) NOT NULL,
  `clinical_info` text NOT NULL,
  `service_code` varchar(10) DEFAULT NULL,
  `price_cash` decimal(10,2) DEFAULT NULL COMMENT 'net price',
  `price_cash_orig` decimal(10,2) DEFAULT NULL COMMENT 'original cash price',
  `price_charge` decimal(10,2) DEFAULT NULL COMMENT 'original charge price',
  `service_date` date DEFAULT '0000-00-00',
  `is_in_house` tinyint(1) DEFAULT NULL,
  `request_doctor` varchar(50) DEFAULT NULL,
  `request_date` date NOT NULL DEFAULT '0000-00-00' COMMENT 'to be deleted 09252007; use the request_date in seg_radio_serv',
  `encoder` varchar(50) DEFAULT NULL,
  `status` varchar(10) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `parent_batch_nr` int(10) DEFAULT NULL,
  `parent_refno` varchar(10) DEFAULT NULL,
  `approved_by_head` varchar(50) DEFAULT NULL,
  `remarks` text,
  `headID` varchar(35) DEFAULT NULL,
  `headpasswd` varchar(255) DEFAULT NULL,
  `request_flag` varchar(10) DEFAULT NULL,
  `cancel_reason` text,
  `or_number` varchar(12) DEFAULT NULL,
  `is_served` tinyint(1) DEFAULT '0',
  `served_date` datetime DEFAULT NULL,
  `rad_tech` int(11) DEFAULT NULL,
  PRIMARY KEY (`batch_nr`),
  UNIQUE KEY `FK_care_test_request_radio` (`refno`,`service_code`),
  KEY `FK_care_test_request_radio_services` (`service_code`),
  KEY `FK_care_test_request_radio_charge` (`request_flag`),
  KEY `refno_index` (`refno`),
  KEY `status_index` (`status`),
  CONSTRAINT `FK_care_test_request_radio` FOREIGN KEY (`refno`) REFERENCES `seg_radio_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_care_test_request_radio_charge` FOREIGN KEY (`request_flag`) REFERENCES `seg_type_charge` (`id`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
