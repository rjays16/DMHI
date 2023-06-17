
DROP TABLE IF EXISTS `seg_opaccommodation`;
CREATE TABLE `seg_opaccommodation` (
  `refno` varchar(12) NOT NULL DEFAULT '',
  `chrge_dte` datetime NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `encounternr_index` (`encounter_nr`)
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
