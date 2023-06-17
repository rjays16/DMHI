
DROP TABLE IF EXISTS `seg_ops_personell`;
CREATE TABLE `seg_ops_personell` (
  `refno` varchar(12) NOT NULL,
  `dr_nr` int(11) NOT NULL,
  `role_entry_no` smallint(6) unsigned NOT NULL DEFAULT '0',
  `role_type_nr` smallint(5) unsigned NOT NULL,
  `role_type_level` int(10) unsigned DEFAULT NULL,
  `ops_code` varchar(12) NOT NULL,
  `ops_charge` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`,`dr_nr`,`role_entry_no`),
  KEY `FK_seg_ops_personell` (`ops_code`),
  KEY `FK_seg_ops_personell_care_personell` (`dr_nr`),
  KEY `FK_seg_ops_personell_role_person` (`role_type_nr`),
  CONSTRAINT `FK_seg_ops_personell_care_personell` FOREIGN KEY (`dr_nr`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_ops_personell_ops_rvs` FOREIGN KEY (`ops_code`) REFERENCES `seg_ops_rvs` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_ops_personell_role_person` FOREIGN KEY (`role_type_nr`) REFERENCES `care_role_person` (`nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_ops_personell_serv_h` FOREIGN KEY (`refno`) REFERENCES `seg_ops_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
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
