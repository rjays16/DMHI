
DROP TABLE IF EXISTS `care_encounter_procedure`;
CREATE TABLE `care_encounter_procedure` (
  `procedure_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `encounter_type` smallint(5) NOT NULL,
  `type_nr` smallint(5) NOT NULL,
  `op_nr` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `code` varchar(25) NOT NULL,
  `code_parent` varchar(25) NOT NULL DEFAULT '',
  `group_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `code_version` tinyint(4) NOT NULL DEFAULT '0',
  `localcode` varchar(35) NOT NULL DEFAULT '',
  `category_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `localization` varchar(35) NOT NULL DEFAULT '',
  `responsible_clinician` varchar(60) NOT NULL DEFAULT '',
  `responsible_dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referral_nr` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`procedure_nr`),
  KEY `encounter_nr` (`encounter_nr`),
  KEY `FK_care_encounter_procedure_icp2` (`code`),
  KEY `status_index` (`status`),
  CONSTRAINT `FK_care_encounter_procedure` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_care_encounter_procedure_ICP` FOREIGN KEY (`code`) REFERENCES `care_ops301_en` (`code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
