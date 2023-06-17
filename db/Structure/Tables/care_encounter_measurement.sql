
DROP TABLE IF EXISTS `care_encounter_measurement`;
CREATE TABLE `care_encounter_measurement` (
  `nr` int(11) unsigned NOT NULL,
  `msr_date` date NOT NULL DEFAULT '0000-00-00',
  `msr_time` float(4,2) NOT NULL DEFAULT '0.00',
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `msr_type_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  `unit_nr` smallint(5) unsigned DEFAULT NULL,
  `unit_type_nr` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(255) DEFAULT NULL,
  `measured_by` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `type` (`msr_type_nr`),
  KEY `encounter_nr` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_measurement` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
