
DROP TABLE IF EXISTS `care_test_findings_radio_doc_nr`;
CREATE TABLE `care_test_findings_radio_doc_nr` (
  `batch_nr` int(10) unsigned NOT NULL,
  `finding_nr` varchar(10) NOT NULL,
  `con_doctor_nr` varchar(50) DEFAULT NULL,
  `sen_doctor_nr` varchar(50) DEFAULT NULL,
  `jun_doctor_nr` varchar(50) DEFAULT NULL,
  KEY `NewIndex1` (`batch_nr`,`finding_nr`)
);
