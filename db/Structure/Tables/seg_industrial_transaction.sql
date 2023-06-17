
DROP TABLE IF EXISTS `seg_industrial_transaction`;
CREATE TABLE `seg_industrial_transaction` (
  `refno` varchar(12) NOT NULL,
  `trxn_date` datetime DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `purpose_exam` varchar(10) DEFAULT NULL,
  `remarks` text,
  `agency_charged` tinyint(1) DEFAULT NULL,
  `agency_id` varchar(12) DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `history` text,
  `status` varchar(35) DEFAULT NULL COMMENT 'logically deleted, cancelled transactions.',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_industrial_transaction_purpose` (`purpose_exam`),
  KEY `FK_seg_industrial_transaction_enc` (`encounter_nr`),
  KEY `FK_seg_industrial_transaction` (`pid`),
  KEY `FK_seg_industrial_transaction_2` (`agency_id`),
  CONSTRAINT `FK_seg_industrial_transaction` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_seg_industrial_transaction_2` FOREIGN KEY (`agency_id`) REFERENCES `seg_industrial_company` (`company_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_industrial_transaction_enc` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_industrial_transaction_purpose` FOREIGN KEY (`purpose_exam`) REFERENCES `seg_industrial_purpose` (`id`) ON UPDATE CASCADE
);
