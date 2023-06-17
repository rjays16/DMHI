
DROP TABLE IF EXISTS `care_encounter_image`;
CREATE TABLE `care_encounter_image` (
  `nr` bigint(20) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `shot_date` date NOT NULL DEFAULT '0000-00-00',
  `shot_nr` smallint(6) NOT NULL DEFAULT '0',
  `mime_type` varchar(10) NOT NULL DEFAULT '',
  `upload_date` date NOT NULL DEFAULT '0000-00-00',
  `notes` text NOT NULL,
  `graphic_script` text NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_image` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
