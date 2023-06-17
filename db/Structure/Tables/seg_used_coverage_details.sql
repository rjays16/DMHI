
DROP TABLE IF EXISTS `seg_used_coverage_details`;
CREATE TABLE `seg_used_coverage_details` (
  `disclose_id` varchar(12) NOT NULL DEFAULT '',
  `hcare_id` int(8) unsigned NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `used_acc_coverage` decimal(20,4) NOT NULL,
  `used_med_coverage` decimal(20,4) NOT NULL,
  `used_sup_coverage` decimal(20,4) NOT NULL,
  `used_srv_coverage` decimal(20,4) NOT NULL,
  `used_ops_coverage` decimal(20,4) NOT NULL,
  `used_d1_coverage` decimal(20,4) NOT NULL,
  `used_d2_coverage` decimal(20,4) NOT NULL,
  `used_d3_coverage` decimal(20,4) NOT NULL,
  `used_d4_coverage` decimal(20,4) NOT NULL,
  `used_msc_coverage` decimal(20,4) NOT NULL,
  `used_days_covered` int(10) unsigned NOT NULL,
  PRIMARY KEY (`disclose_id`,`hcare_id`,`entry_no`),
  KEY `FK_seg_used_coverage_details_insurance_firm` (`hcare_id`),
  CONSTRAINT `FK_seg_used_coverage_details_hdr` FOREIGN KEY (`disclose_id`) REFERENCES `seg_used_coverage` (`disclose_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_used_coverage_details_insurance_firm` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
