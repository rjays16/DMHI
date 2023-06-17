
DROP TABLE IF EXISTS `care_test_findings_radio`;
CREATE TABLE `care_test_findings_radio` (
  `batch_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `findings` longtext NOT NULL,
  `radio_impression` text NOT NULL,
  `findings_date` text NOT NULL,
  `findings_date_old` date NOT NULL DEFAULT '0000-00-00',
  `doctor_in_charge` text,
  `result_status` text,
  `encoder` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `send_date` (`findings_date`(3)),
  KEY `findings_date` (`findings_date`(3)),
  CONSTRAINT `FK_care_test_findings_radio_request` FOREIGN KEY (`batch_nr`) REFERENCES `care_test_request_radio` (`batch_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
