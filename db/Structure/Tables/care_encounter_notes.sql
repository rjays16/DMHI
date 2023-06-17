
DROP TABLE IF EXISTS `care_encounter_notes`;
CREATE TABLE `care_encounter_notes` (
  `nr` int(10) unsigned NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `short_notes` varchar(25) DEFAULT NULL,
  `aux_notes` varchar(255) DEFAULT NULL,
  `ref_notes_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `personell_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `personell_name` varchar(60) NOT NULL DEFAULT '',
  `send_to_pid` int(11) NOT NULL DEFAULT '0',
  `send_to_name` varchar(60) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `location_type` varchar(35) DEFAULT NULL,
  `location_type_nr` tinyint(3) NOT NULL DEFAULT '0',
  `location_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `location_id` varchar(60) DEFAULT NULL,
  `ack_short_id` varchar(10) NOT NULL DEFAULT '',
  `date_ack` datetime DEFAULT NULL,
  `date_checked` datetime DEFAULT NULL,
  `date_printed` datetime DEFAULT NULL,
  `send_by_mail` tinyint(1) DEFAULT NULL,
  `send_by_email` tinyint(1) DEFAULT NULL,
  `send_by_fax` tinyint(1) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`),
  KEY `type_nr` (`type_nr`),
  CONSTRAINT `FK_care_encounter_notes` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
