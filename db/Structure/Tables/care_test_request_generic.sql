
DROP TABLE IF EXISTS `care_test_request_generic`;
CREATE TABLE `care_test_request_generic` (
  `batch_nr` int(11) NOT NULL DEFAULT '0',
  `encounter_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `testing_dept` varchar(35) NOT NULL DEFAULT '',
  `visit` tinyint(1) NOT NULL DEFAULT '0',
  `order_patient` tinyint(1) NOT NULL DEFAULT '0',
  `diagnosis_quiry` text NOT NULL,
  `send_date` date NOT NULL DEFAULT '0000-00-00',
  `send_doctor` varchar(35) NOT NULL DEFAULT '',
  `result` text NOT NULL,
  `result_date` date NOT NULL DEFAULT '0000-00-00',
  `result_doctor` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `batch_nr` (`batch_nr`,`encounter_nr`),
  KEY `send_date` (`send_date`)
);
