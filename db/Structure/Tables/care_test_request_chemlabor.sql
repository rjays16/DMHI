
DROP TABLE IF EXISTS `care_test_request_chemlabor`;
CREATE TABLE `care_test_request_chemlabor` (
  `batch_nr` int(11) NOT NULL,
  `encounter_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `room_nr` varchar(10) NOT NULL DEFAULT '',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `parameters` text NOT NULL,
  `doctor_sign` varchar(35) NOT NULL DEFAULT '',
  `highrisk` smallint(1) NOT NULL DEFAULT '0',
  `notes` tinytext NOT NULL,
  `send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sample_time` time NOT NULL DEFAULT '00:00:00',
  `sample_weekday` smallint(1) NOT NULL DEFAULT '0',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `encounter_nr` (`encounter_nr`)
);
