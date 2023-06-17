
DROP TABLE IF EXISTS `seg_discount`;
CREATE TABLE `seg_discount` (
  `discountid` varchar(10) NOT NULL,
  `discountdesc` varchar(80) NOT NULL DEFAULT '',
  `discount` decimal(10,8) NOT NULL,
  `area_used` enum('P','O','L','R','B') DEFAULT NULL,
  `is_forall` tinyint(1) NOT NULL DEFAULT '0',
  `is_charity` tinyint(1) NOT NULL DEFAULT '0',
  `lockflag` tinyint(1) NOT NULL DEFAULT '0',
  `parentid` varchar(10) NOT NULL,
  `billareas_applied` text NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `allow_walkin` tinyint(1) DEFAULT '0',
  `is_visible` tinyint(1) DEFAULT '1',
  `is_additional_support` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`discountid`)
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
