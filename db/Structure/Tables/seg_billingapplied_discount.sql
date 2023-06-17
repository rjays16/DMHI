
DROP TABLE IF EXISTS `seg_billingapplied_discount`;
CREATE TABLE `seg_billingapplied_discount` (
  `encounter_nr` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `entry_dte` datetime NOT NULL,
  `discountid` varchar(10) NOT NULL,
  `discountdesc` varchar(80) NOT NULL,
  `discount` decimal(10,8) NOT NULL,
  `discount_amnt` double(10,2) DEFAULT NULL,
  `remarks` tinytext,
  `billareas_applied` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`entry_no`),
  CONSTRAINT `FK_seg_billingapplied_discount_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
