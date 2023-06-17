
DROP TABLE IF EXISTS `care_personell_assignment`;
CREATE TABLE `care_personell_assignment` (
  `nr` int(10) unsigned NOT NULL,
  `personell_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `role_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `location_type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `location_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `is_temporary` tinyint(1) unsigned DEFAULT NULL,
  `list_frequency` int(11) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`,`personell_nr`,`role_nr`,`location_type_nr`,`location_nr`),
  KEY `personell_nr` (`personell_nr`)
);
