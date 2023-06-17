
DROP TABLE IF EXISTS `care_test_findings_patho`;
CREATE TABLE `care_test_findings_patho` (
  `batch_nr` int(11) NOT NULL DEFAULT '0',
  `encounter_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `room_nr` varchar(10) NOT NULL DEFAULT '',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `material` text NOT NULL,
  `macro` text NOT NULL,
  `micro` text NOT NULL,
  `findings` text NOT NULL,
  `diagnosis` text NOT NULL,
  `doctor_id` varchar(35) NOT NULL DEFAULT '',
  `findings_date` date NOT NULL DEFAULT '0000-00-00',
  `findings_time` time NOT NULL DEFAULT '00:00:00',
  `status` varchar(10) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`,`encounter_nr`,`room_nr`,`dept_nr`),
  KEY `send_date` (`findings_date`),
  KEY `findings_date` (`findings_date`)
);
