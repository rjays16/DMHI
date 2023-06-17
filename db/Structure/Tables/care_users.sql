
DROP TABLE IF EXISTS `care_users`;
CREATE TABLE `care_users` (
  `name` varchar(60) NOT NULL DEFAULT '',
  `login_id` varchar(35) NOT NULL DEFAULT '',
  `password` varchar(255) DEFAULT NULL,
  `personell_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `lockflag` tinyint(3) unsigned DEFAULT '0',
  `permission` text NOT NULL,
  `exc` tinyint(1) NOT NULL DEFAULT '0',
  `s_date` date NOT NULL DEFAULT '0000-00-00',
  `s_time` time NOT NULL DEFAULT '00:00:00',
  `expire_date` date NOT NULL DEFAULT '0000-00-00',
  `expire_time` time NOT NULL DEFAULT '00:00:00',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`login_id`),
  KEY `login_id` (`login_id`)
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
