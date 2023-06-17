
DROP TABLE IF EXISTS `seg_ops_serv`;
CREATE TABLE `seg_ops_serv` (
  `refno` varchar(12) NOT NULL,
  `nr` int(11) DEFAULT NULL COMMENT 'nr from care_encounter_op',
  `request_date` date NOT NULL,
  `request_time` time NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `is_cash` tinyint(1) NOT NULL,
  `is_urgent` tinyint(1) NOT NULL,
  `ordername` varchar(200) DEFAULT NULL,
  `orderaddress` varchar(300) DEFAULT NULL,
  `hasPaid` tinyint(1) DEFAULT '0',
  `comments` varchar(200) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`),
  KEY `FK_seg_ops_serv_encounter` (`encounter_nr`),
  KEY `FK_seg_ops_serv_person` (`pid`),
  CONSTRAINT `FK_seg_ops_serv_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_ops_serv_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
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
