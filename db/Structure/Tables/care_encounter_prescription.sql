
DROP TABLE IF EXISTS `care_encounter_prescription`;
CREATE TABLE `care_encounter_prescription` (
  `nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `prescription_type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `article` varchar(100) NOT NULL DEFAULT '',
  `drug_class` varchar(60) NOT NULL DEFAULT '',
  `order_nr` int(11) NOT NULL DEFAULT '0',
  `dosage` varchar(255) NOT NULL DEFAULT '',
  `application_type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `prescribe_date` date DEFAULT NULL,
  `prescriber` varchar(60) NOT NULL DEFAULT '',
  `color_marker` varchar(10) NOT NULL DEFAULT '',
  `is_stopped` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stop_date` date DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_prescription` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
