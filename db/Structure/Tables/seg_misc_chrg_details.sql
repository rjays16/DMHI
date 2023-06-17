
DROP TABLE IF EXISTS `seg_misc_chrg_details`;
CREATE TABLE `seg_misc_chrg_details` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `account_type` int(11) DEFAULT NULL,
  `chrg_amnt` decimal(20,4) NOT NULL,
  `quantity` float unsigned NOT NULL DEFAULT '1',
  `request_flag` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`entry_no`),
  KEY `FK_seg_misc_chrg_details_other_services` (`service_code`),
  CONSTRAINT `FK_seg_misc_chrg_details_chrg_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_misc_chrg` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_misc_chrg_details_other_services` FOREIGN KEY (`service_code`) REFERENCES `seg_other_services` (`service_code`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
