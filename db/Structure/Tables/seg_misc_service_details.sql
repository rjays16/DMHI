
DROP TABLE IF EXISTS `seg_misc_service_details`;
CREATE TABLE `seg_misc_service_details` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `account_type` int(11) NOT NULL,
  `adjusted_amnt` decimal(20,4) DEFAULT NULL,
  `chrg_amnt` decimal(20,4) NOT NULL,
  `quantity` float unsigned DEFAULT '1',
  `request_flag` varchar(10) DEFAULT NULL,
  `cancel_reason` text,
  `clinical_info` text,
  PRIMARY KEY (`refno`,`service_code`,`entry_no`),
  KEY `FK_seg_misc_service_details_otherhosp_service` (`service_code`),
  CONSTRAINT `FK_seg_misc_service_details_2` FOREIGN KEY (`service_code`) REFERENCES `seg_other_services` (`alt_service_code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_misc_service_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_misc_service` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
