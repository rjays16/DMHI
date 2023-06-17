
DROP TABLE IF EXISTS `care_med_report`;
CREATE TABLE `care_med_report` (
  `report_nr` int(11) NOT NULL,
  `dept` varchar(15) NOT NULL DEFAULT '',
  `report` text NOT NULL,
  `reporter` varchar(25) NOT NULL DEFAULT '',
  `id_nr` varchar(15) NOT NULL DEFAULT '',
  `report_date` date NOT NULL DEFAULT '0000-00-00',
  `report_time` time NOT NULL DEFAULT '00:00:00',
  `status` varchar(20) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`report_nr`),
  KEY `report_nr` (`report_nr`)
);
