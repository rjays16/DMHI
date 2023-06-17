
DROP TABLE IF EXISTS `care_encounter_diagnosis`;
CREATE TABLE `care_encounter_diagnosis` (
  `diagnosis_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `encounter_type` smallint(5) NOT NULL,
  `type_nr` smallint(5) NOT NULL,
  `op_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `code` varchar(25) NOT NULL DEFAULT '',
  `code_parent` varchar(25) NOT NULL DEFAULT '',
  `group_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `code_version` tinyint(4) NOT NULL DEFAULT '0',
  `localcode` varchar(35) NOT NULL DEFAULT '',
  `category_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(35) NOT NULL DEFAULT '',
  `localization` varchar(35) NOT NULL DEFAULT '',
  `diagnosing_clinician` varchar(60) NOT NULL DEFAULT '',
  `diagnosing_dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referral_nr` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`diagnosis_nr`),
  KEY `encounter_nr2` (`encounter_nr`),
  KEY `FK_care_encounter_diagnosis_icd2_2` (`code`),
  KEY `code_index` (`code`),
  CONSTRAINT `FK_care_encounter_diagnosis_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_care_encounter_diagnosis_icd` FOREIGN KEY (`code`) REFERENCES `care_icd10_en` (`diagnosis_code`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
