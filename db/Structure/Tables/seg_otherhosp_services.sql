
DROP TABLE IF EXISTS `seg_otherhosp_services`;
CREATE TABLE `seg_otherhosp_services` (
  `service_code` varchar(12) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` double(10,4) NOT NULL DEFAULT '0.0000',
  `chrgprice` double(10,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(25) NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `account_type` int(11) DEFAULT NULL,
  `exclude_hcareid` int(8) unsigned NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`service_code`),
  KEY `FK_seg_otherhosp_services_subtypes` (`account_type`),
  CONSTRAINT `FK_seg_otherhosp_services_subtypes` FOREIGN KEY (`account_type`) REFERENCES `seg_cashier_account_subtypes` (`type_id`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
