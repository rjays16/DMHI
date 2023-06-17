
DROP TABLE IF EXISTS `seg_dialysis_transaction`;
CREATE TABLE `seg_dialysis_transaction` (
  `refno` varchar(12) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `status` enum('DONE','UNDONE') DEFAULT 'UNDONE',
  `dialysis_type` enum('BEFORE','AFTER') DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `requesting_doctor` varchar(12) DEFAULT NULL,
  `attending_nurse` varchar(12) DEFAULT NULL,
  `remarks` text,
  `reason` text,
  `create_id` varchar(12) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(12) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`encounter_nr`),
  KEY `FK_seg_dialysis_transaction` (`encounter_nr`),
  CONSTRAINT `FK_seg_dialysis_transaction` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE
);
