
DROP TABLE IF EXISTS `seg_encounter_confinement`;
CREATE TABLE `seg_encounter_confinement` (
  `encounter_nr` varchar(12) NOT NULL,
  `confinetype_id` smallint(5) unsigned NOT NULL,
  `classify_id` varchar(35) NOT NULL,
  `classify_dte` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`confinetype_id`,`classify_dte`),
  KEY `FK_seg_encounter_classification_confinement` (`confinetype_id`),
  CONSTRAINT `FK_seg_encounter_classification_confinement` FOREIGN KEY (`confinetype_id`) REFERENCES `seg_type_confinement` (`confinetype_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_classification_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
