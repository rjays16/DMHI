
DROP TABLE IF EXISTS `seg_med_retail`;
CREATE TABLE `seg_med_retail` (
  `refno` varchar(20) NOT NULL,
  `purchasedte` datetime NOT NULL,
  `encounter_nr` bigint(11) unsigned DEFAULT NULL,
  `is_cash` tinyint(1) NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_med_retail_1` (`create_id`),
  KEY `FK_seg_med_retail_2` (`modify_id`),
  CONSTRAINT `seg_med_retail_ibfk_1` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE,
  CONSTRAINT `seg_med_retail_ibfk_2` FOREIGN KEY (`modify_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
