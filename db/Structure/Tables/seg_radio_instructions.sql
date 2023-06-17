
DROP TABLE IF EXISTS `seg_radio_instructions`;
CREATE TABLE `seg_radio_instructions` (
  `nr` int(11) unsigned NOT NULL,
  `instruction` varchar(250) DEFAULT NULL,
  `dept_nr` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`nr`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
