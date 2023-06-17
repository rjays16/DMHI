
DROP TABLE IF EXISTS `seg_charity_grants`;
CREATE TABLE `seg_charity_grants` (
  `encounter_nr` varchar(12) NOT NULL,
  `grant_dte` datetime NOT NULL,
  `sw_nr` int(11) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  `discount` decimal(10,8) NOT NULL,
  `discount_amnt` decimal(10,2) DEFAULT NULL,
  `notes` text,
  `personal_circumstance` text,
  `community_situation` text,
  `nature_of_disease` text,
  `reason` varchar(10) DEFAULT NULL,
  `other_name` varchar(10) DEFAULT NULL,
  `id_number` varchar(20) DEFAULT NULL,
  `status` enum('valid','expired','cancelled','deleted') DEFAULT 'valid',
  PRIMARY KEY (`encounter_nr`,`grant_dte`),
  KEY `FK_seg_charity_grants_personel` (`sw_nr`),
  KEY `discountid` (`discountid`),
  KEY `encounternr_index` (`encounter_nr`),
  CONSTRAINT `FK_seg_charity_grants_discount` FOREIGN KEY (`discountid`) REFERENCES `seg_discount` (`discountid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_charity_grants_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_charity_grants_personel` FOREIGN KEY (`sw_nr`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
