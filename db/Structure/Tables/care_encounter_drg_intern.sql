
DROP TABLE IF EXISTS `care_encounter_drg_intern`;
CREATE TABLE `care_encounter_drg_intern` (
  `nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `group_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `clinician` varchar(60) NOT NULL DEFAULT '',
  `dept_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`),
  KEY `status_index` (`status`),
  CONSTRAINT `FK_care_encounter_drg_intern` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
