
DROP TABLE IF EXISTS `seg_pay_deposit`;
CREATE TABLE `seg_pay_deposit` (
  `or_no` varchar(12) NOT NULL,
  `encounter_nr` varchar(12) DEFAULT '',
  `deposit` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `ref_no` varchar(12) DEFAULT NULL,
  `ref_source` enum('LD','RD','OR','PH','MD') DEFAULT NULL,
  PRIMARY KEY (`or_no`),
  KEY `FK_seg_pay_deposit_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_pay_deposit` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pay_deposit_seg_pay` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE
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
