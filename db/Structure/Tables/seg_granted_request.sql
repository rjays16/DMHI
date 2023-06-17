
DROP TABLE IF EXISTS `seg_granted_request`;
CREATE TABLE `seg_granted_request` (
  `grant_no` int(12) NOT NULL COMMENT 'System-generated.',
  `ref_no` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD') NOT NULL,
  `service_code` varchar(12) NOT NULL,
  PRIMARY KEY (`grant_no`),
  UNIQUE KEY `ref_no_service_code` (`ref_no`,`ref_source`,`service_code`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
