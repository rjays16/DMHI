
DROP TABLE IF EXISTS `care_encounter_immunization`;
CREATE TABLE `care_encounter_immunization` (
  `nr` int(10) unsigned NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `type` varchar(60) NOT NULL DEFAULT '',
  `medicine` varchar(60) NOT NULL DEFAULT '',
  `dosage` varchar(60) DEFAULT NULL,
  `application_type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `application_by` varchar(60) DEFAULT NULL,
  `titer` varchar(15) DEFAULT NULL,
  `refresh_date` date DEFAULT NULL,
  `notes` text,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `FK_care_encounter_immunization` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_immunization` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
