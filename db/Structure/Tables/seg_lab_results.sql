
DROP TABLE IF EXISTS `seg_lab_results`;
CREATE TABLE `seg_lab_results` (
  `refno` varchar(12) NOT NULL,
  `trx_dt` datetime DEFAULT NULL,
  `trx_status` varchar(50) DEFAULT NULL,
  `order_no` varchar(20) DEFAULT NULL,
  `order_dt` datetime DEFAULT NULL,
  `loc_code` varchar(20) DEFAULT NULL,
  `dr_code` varchar(20) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `patient_caseNo` varchar(20) DEFAULT NULL,
  `clinical_info` varchar(100) DEFAULT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `lab_no` varchar(50) DEFAULT NULL,
  `service_code` varchar(50) DEFAULT NULL,
  `test_type` varchar(100) DEFAULT NULL,
  `tg_code` varchar(50) DEFAULT NULL,
  `ctl_seqNo` int(11) DEFAULT NULL,
  PRIMARY KEY (`refno`),
  KEY `FK_seg_lab_results` (`pid`),
  KEY `NewIndex1` (`order_no`),
  CONSTRAINT `FK_seg_lab_results` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
