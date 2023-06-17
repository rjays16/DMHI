
DROP TABLE IF EXISTS `seg_misc_ops_details`;
CREATE TABLE `seg_misc_ops_details` (
  `refno` varchar(12) NOT NULL,
  `ops_code` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `op_date` date NOT NULL,
  `rvu` int(10) unsigned NOT NULL,
  `multiplier` double(10,4) NOT NULL,
  `chrg_amnt` double(20,4) NOT NULL,
  `group_code` varchar(4) NOT NULL,
  `laterality` enum('L','R','B') NOT NULL,
  PRIMARY KEY (`refno`,`ops_code`,`entry_no`),
  KEY `FK_seg_misc_ops_details_care_ops301_en` (`ops_code`),
  CONSTRAINT `FK_seg_misc_ops_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_misc_ops` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_misc_ops_details_seg_ops_rvs` FOREIGN KEY (`ops_code`) REFERENCES `seg_ops_rvs` (`code`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
