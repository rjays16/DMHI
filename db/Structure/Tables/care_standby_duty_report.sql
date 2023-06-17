
DROP TABLE IF EXISTS `care_standby_duty_report`;
CREATE TABLE `care_standby_duty_report` (
  `report_nr` int(11) NOT NULL,
  `dept` varchar(15) NOT NULL DEFAULT '',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `standby_name` varchar(35) NOT NULL DEFAULT '',
  `standby_start` time NOT NULL DEFAULT '00:00:00',
  `standby_end` time NOT NULL DEFAULT '00:00:00',
  `oncall_name` varchar(35) NOT NULL DEFAULT '',
  `oncall_start` time NOT NULL DEFAULT '00:00:00',
  `oncall_end` time NOT NULL DEFAULT '00:00:00',
  `op_room` char(2) NOT NULL DEFAULT '',
  `diagnosis_therapy` text NOT NULL,
  `encoding` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`report_nr`),
  KEY `report_nr` (`report_nr`)
);
