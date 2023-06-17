
DROP TABLE IF EXISTS `seg_encounter_dr_mgt`;
CREATE TABLE `seg_encounter_dr_mgt` (
  `encounter_nr` varchar(12) NOT NULL,
  `attending_dr_nr` int(11) NOT NULL DEFAULT '0',
  `att_hist_no` int(4) unsigned NOT NULL,
  `attend_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `daily_rate` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`attending_dr_nr`,`att_hist_no`),
  KEY `FK_seg_encounter_dr_mgt_personell` (`attending_dr_nr`),
  CONSTRAINT `FK_seg_encounter_dr_mgt_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_dr_mgt_personell` FOREIGN KEY (`attending_dr_nr`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
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
