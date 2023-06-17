
DROP TABLE IF EXISTS `seg_misc_service`;
CREATE TABLE `seg_misc_service` (
  `refno` varchar(12) NOT NULL,
  `chrge_dte` datetime NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `discount` decimal(10,8) DEFAULT NULL,
  `is_cash` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `request_source` varchar(10) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `area` varchar(10) DEFAULT '',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_misc_service_encounter` (`encounter_nr`),
  KEY `FK_seg_misc_service_requestsrc` (`request_source`),
  KEY `chrgedte_index` (`chrge_dte`),
  CONSTRAINT `FK_seg_misc_service_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_misc_service_requestsrc` FOREIGN KEY (`request_source`) REFERENCES `seg_type_request_source` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
