
DROP TABLE IF EXISTS `seg_indigent_charges`;
CREATE TABLE `seg_indigent_charges` (
  `encounter_nr` varchar(12) NOT NULL DEFAULT '',
  `entry_no` char(36) NOT NULL DEFAULT '',
  `charge_desc` varchar(150) DEFAULT NULL,
  `actual` double(12,2) DEFAULT NULL,
  `phic` double(12,2) DEFAULT NULL,
  `discount` double(12,2) DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`entry_no`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
