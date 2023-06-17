
DROP TABLE IF EXISTS `seg_encounter_location_addtl`;
CREATE TABLE `seg_encounter_location_addtl` (
  `encounter_nr` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `room_nr` mediumint(8) unsigned NOT NULL,
  `group_nr` smallint(5) unsigned NOT NULL,
  `bed_nr` smallint(5) unsigned NOT NULL,
  `days_stay` int(10) unsigned NOT NULL,
  `hrs_stay` int(10) unsigned NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `occupy_date` date DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `occupy_date_to` date DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `occupy_date_from` date DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`entry_no`),
  CONSTRAINT `FK_seg_encounter_location_addtl_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
