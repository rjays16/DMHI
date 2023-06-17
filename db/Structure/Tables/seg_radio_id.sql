
DROP TABLE IF EXISTS `seg_radio_id`;
CREATE TABLE `seg_radio_id` (
  `rid` varchar(10) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `status` varchar(15) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`rid`),
  KEY `FK_seg_radio_id` (`pid`),
  CONSTRAINT `FK_seg_radio_id` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
