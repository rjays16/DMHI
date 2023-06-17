
DROP TABLE IF EXISTS `care_encounter_financial_class`;
CREATE TABLE `care_encounter_financial_class` (
  `nr` bigint(20) unsigned NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `class_nr` smallint(3) unsigned NOT NULL DEFAULT '0',
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `date_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `FK_care_encounter_financial_class` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_financial_class` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
