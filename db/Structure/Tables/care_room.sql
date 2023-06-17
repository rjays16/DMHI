
DROP TABLE IF EXISTS `care_room`;
CREATE TABLE `care_room` (
  `nr` int(8) unsigned NOT NULL,
  `type_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date_create` date NOT NULL DEFAULT '0000-00-00',
  `date_close` date NOT NULL DEFAULT '0000-00-00',
  `is_temp_closed` tinyint(1) DEFAULT '0',
  `room_nr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ward_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'check OR ward before deleting',
  `nr_of_beds` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `closed_beds` varchar(255) NOT NULL DEFAULT '',
  `info` varchar(60) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`,`type_nr`,`ward_nr`,`dept_nr`),
  KEY `room_nr` (`room_nr`),
  KEY `ward_nr` (`ward_nr`),
  KEY `dept_nr` (`dept_nr`),
  KEY `FK_care_room_type_room` (`type_nr`),
  CONSTRAINT `FK_care_room` FOREIGN KEY (`ward_nr`) REFERENCES `care_ward` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_care_room_type_room` FOREIGN KEY (`type_nr`) REFERENCES `care_type_room` (`nr`) ON UPDATE CASCADE
);
