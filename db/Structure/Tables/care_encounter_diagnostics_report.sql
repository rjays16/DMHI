
DROP TABLE IF EXISTS `care_encounter_diagnostics_report`;
CREATE TABLE `care_encounter_diagnostics_report` (
  `item_nr` int(11) NOT NULL,
  `report_nr` int(11) NOT NULL DEFAULT '0',
  `reporting_dept_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `reporting_dept` varchar(100) NOT NULL DEFAULT '',
  `report_date` date NOT NULL DEFAULT '0000-00-00',
  `report_time` time NOT NULL DEFAULT '00:00:00',
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `script_call` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`item_nr`,`report_nr`),
  KEY `report_nr` (`report_nr`),
  KEY `FK_care_encounter_diagnostics_report` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_diagnostics_report` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
