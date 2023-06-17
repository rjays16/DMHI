
DROP TABLE IF EXISTS `care_encounter_location2`;
CREATE TABLE `care_encounter_location2` (
  `nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `location_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_from` date NOT NULL DEFAULT '0000-00-00',
  `date_to` date NOT NULL DEFAULT '0000-00-00',
  `time_from` time DEFAULT '00:00:00',
  `time_to` time DEFAULT NULL,
  `discharge_type_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `source_assign` enum('NURSING','ADMISSION') DEFAULT NULL,
  PRIMARY KEY (`nr`,`location_nr`),
  KEY `type2` (`type_nr`),
  KEY `location_id2` (`location_nr`),
  KEY `FK_care_encounter_location2` (`encounter_nr`),
  KEY `groupnr_index2` (`group_nr`),
  KEY `datefrom_index2` (`date_from`),
  KEY `dateto_index2` (`date_to`)
);
