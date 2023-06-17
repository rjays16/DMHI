
DROP TABLE IF EXISTS `care_test_findings_radio_index_nr`;
CREATE TABLE `care_test_findings_radio_index_nr` (
  `batch_nr` int(10) unsigned NOT NULL,
  `finding_nr` varchar(10) NOT NULL,
  `radio_index` varchar(1000) NOT NULL
);
