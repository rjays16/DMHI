
DROP TABLE IF EXISTS `seg_hcare_bsked`;
CREATE TABLE `seg_hcare_bsked` (
  `hcare_id` int(8) unsigned NOT NULL,
  `benefit_id` int(11) unsigned NOT NULL,
  `tier_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `basis` tinyint(3) unsigned NOT NULL COMMENT '1 - based on confinement; 2 - based on room type; 3 - based on RVU; 4 - based on per item',
  `effectvty_dte` date NOT NULL,
  `bsked_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`bsked_id`),
  KEY `FK_seg_hcare_bsked_benefits` (`benefit_id`),
  KEY `FK_seg_hcare_bsked_care_insurance_firm` (`hcare_id`),
  CONSTRAINT `FK_seg_hcare_bsked_benefits` FOREIGN KEY (`benefit_id`) REFERENCES `seg_hcare_benefits` (`benefit_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_hcare_bsked_care_insurance_firm` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
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
