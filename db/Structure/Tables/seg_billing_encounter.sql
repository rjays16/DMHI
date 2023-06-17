
DROP TABLE IF EXISTS `seg_billing_encounter`;
CREATE TABLE `seg_billing_encounter` (
  `bill_nr` varchar(12) NOT NULL,
  `bill_dte` datetime NOT NULL,
  `bill_frmdte` datetime NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `accommodation_type` smallint(6) NOT NULL,
  `total_acc_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_med_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_sup_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_srv_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_ops_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_doc_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_msc_charge` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_prevpayments` double(20,4) NOT NULL DEFAULT '0.0000',
  `total_auto_excess` double(20,4) NOT NULL DEFAULT '0.0000',
  `Pay_serNum` varchar(12) DEFAULT NULL,
  `applied_hrs_cutoff` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_final` tinyint(1) NOT NULL,
  `request_flag` varchar(10) DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` tinyint(1) DEFAULT NULL,
  `bill_time_started` datetime DEFAULT NULL,
  `bill_time_ended` datetime DEFAULT NULL,
  PRIMARY KEY (`bill_nr`),
  KEY `FK_seg_billing_encounter_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_billing_encounter_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
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
