
DROP TABLE IF EXISTS `care_encounter_location`;
CREATE TABLE `care_encounter_location` (
  `nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `location_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_from` date NOT NULL DEFAULT '0000-00-00',
  `date_to` date NOT NULL DEFAULT '0000-00-00',
  `time_from` time DEFAULT '00:00:00',
  `time_to` time DEFAULT NULL,
  `discharge_type_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `source_assign` enum('NURSING','ADMISSION') DEFAULT NULL,
  PRIMARY KEY (`nr`,`location_nr`),
  KEY `type` (`type_nr`),
  KEY `location_id` (`location_nr`),
  KEY `FK_care_encounter_location` (`encounter_nr`),
  KEY `groupnr_index` (`group_nr`),
  KEY `datefrom_index` (`date_from`),
  KEY `dateto_index` (`date_to`),
  CONSTRAINT `FK_care_encounter_location` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_care_encounter_location_type_location` FOREIGN KEY (`type_nr`) REFERENCES `care_type_location` (`nr`) ON UPDATE CASCADE
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
