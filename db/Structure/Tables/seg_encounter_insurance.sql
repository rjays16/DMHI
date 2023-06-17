
DROP TABLE IF EXISTS `seg_encounter_insurance`;
CREATE TABLE `seg_encounter_insurance` (
  `encounter_nr` varchar(12) NOT NULL,
  `hcare_id` int(8) NOT NULL,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`hcare_id`),
  KEY `encounternr_index` (`encounter_nr`),
  KEY `hcareid_index` (`hcare_id`),
  KEY `createdt_index` (`create_dt`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
