
DROP TABLE IF EXISTS `seg_encounter_privy_dr`;
CREATE TABLE `seg_encounter_privy_dr` (
  `encounter_nr` varchar(12) NOT NULL,
  `dr_nr` int(11) NOT NULL,
  `dr_role_type_nr` smallint(5) unsigned NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `dr_level` smallint(1) NOT NULL DEFAULT '1',
  `days_attended` int(10) unsigned NOT NULL,
  `dr_charge` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `is_excluded` tinyint(1) NOT NULL DEFAULT '0',
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`dr_nr`,`dr_role_type_nr`,`entry_no`)
);
;
DELIMITER ;
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
