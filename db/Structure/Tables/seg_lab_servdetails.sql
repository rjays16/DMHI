
DROP TABLE IF EXISTS `seg_lab_servdetails`;
CREATE TABLE `seg_lab_servdetails` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `price_cash` decimal(10,2) DEFAULT '0.00',
  `price_cash_orig` decimal(10,2) DEFAULT '0.00',
  `price_charge` decimal(10,2) DEFAULT '0.00',
  `request_doctor` varchar(50) DEFAULT NULL,
  `request_dept` varchar(50) DEFAULT NULL,
  `is_in_house` tinyint(1) DEFAULT NULL,
  `clinical_info` text,
  `status` enum('pending','done','deleted','sent-out') DEFAULT 'pending',
  `is_forward` tinyint(1) DEFAULT '0',
  `is_served` tinyint(1) DEFAULT '0',
  `date_served` datetime DEFAULT NULL,
  `clerk_served_by` varchar(100) DEFAULT NULL,
  `clerk_served_date` datetime DEFAULT NULL,
  `quantity` double DEFAULT '1',
  `old_qty_request` double DEFAULT '0',
  `reason_sent_out` text,
  `sent_out_date` datetime DEFAULT NULL,
  `sent_out_by` varchar(100) DEFAULT NULL,
  `is_monitor` tinyint(1) DEFAULT '0',
  `parent_refno` varchar(12) DEFAULT NULL,
  `request_flag` varchar(10) DEFAULT NULL,
  `no_gel_tubes` double DEFAULT '0',
  `cancel_reason` text,
  `is_posted_lis` tinyint(1) DEFAULT '0',
  `history` text,
  `date_request` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`),
  KEY `FK_seg_lab_servdetails2` (`service_code`),
  KEY `FK_seg_lab_servdetails_charge` (`request_flag`),
  KEY `refno_index` (`refno`),
  CONSTRAINT `FK_seg_lab_servdetails` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lab_servdetails_charge` FOREIGN KEY (`request_flag`) REFERENCES `seg_type_charge` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lab_servdetails_service` FOREIGN KEY (`service_code`) REFERENCES `seg_lab_services` (`service_code`) ON DELETE NO ACTION ON UPDATE CASCADE
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
