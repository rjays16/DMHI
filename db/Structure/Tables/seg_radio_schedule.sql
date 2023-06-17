
DROP TABLE IF EXISTS `seg_radio_schedule`;
CREATE TABLE `seg_radio_schedule` (
  `schedule_no` varchar(12) NOT NULL,
  `batch_nr` int(10) NOT NULL,
  `scheduled_dt` date NOT NULL DEFAULT '0000-00-00',
  `scheduled_time` time DEFAULT '00:00:00',
  `instructions` text,
  `remarks` text,
  `remarks2` text,
  `status` varchar(35) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
