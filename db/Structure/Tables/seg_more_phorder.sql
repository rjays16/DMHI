
DROP TABLE IF EXISTS `seg_more_phorder`;
CREATE TABLE `seg_more_phorder` (
  `refno` varchar(12) NOT NULL,
  `chrge_dte` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `encounter_nr` varchar(12) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_more_phorder_encounter` (`encounter_nr`),
  KEY `FK_seg_more_phorder_areas` (`area_code`),
  CONSTRAINT `FK_seg_more_phorder_areas` FOREIGN KEY (`area_code`) REFERENCES `seg_areas` (`area_code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_more_phorder_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
