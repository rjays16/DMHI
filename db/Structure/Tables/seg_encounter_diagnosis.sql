
DROP TABLE IF EXISTS `seg_encounter_diagnosis`;
CREATE TABLE `seg_encounter_diagnosis` (
  `diagnosis_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `entry_no` smallint(5) NOT NULL,
  `code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `code_alt` varchar(15) DEFAULT NULL,
  `type_nr` smallint(5) DEFAULT '0',
  PRIMARY KEY (`diagnosis_nr`),
  KEY `FK_seg_encounter_diagnosis_care_icd10` (`code`),
  KEY `seg_diagnosis_enc_idx` (`encounter_nr`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
