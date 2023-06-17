
DROP TABLE IF EXISTS `seg_misc_ops`;
CREATE TABLE `seg_misc_ops` (
  `refno` varchar(12) NOT NULL,
  `chrge_dte` datetime NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_misc_ops_care_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_misc_ops_care_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
