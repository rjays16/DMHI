
DROP TABLE IF EXISTS `care_test_findings_chemlab`;
CREATE TABLE `care_test_findings_chemlab` (
  `batch_nr` int(11) NOT NULL,
  `encounter_nr` int(11) NOT NULL DEFAULT '0',
  `job_id` varchar(25) NOT NULL DEFAULT '',
  `test_date` date NOT NULL DEFAULT '0000-00-00',
  `test_time` time NOT NULL DEFAULT '00:00:00',
  `group_id` varchar(30) NOT NULL DEFAULT '',
  `serial_value` text NOT NULL,
  `validator` varchar(15) NOT NULL DEFAULT '',
  `validate_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(20) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`,`encounter_nr`,`job_id`)
);
