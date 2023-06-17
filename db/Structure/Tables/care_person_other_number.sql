
DROP TABLE IF EXISTS `care_person_other_number`;
CREATE TABLE `care_person_other_number` (
  `nr` int(10) unsigned NOT NULL,
  `pid` varchar(12) NOT NULL DEFAULT '0',
  `other_nr` varchar(30) NOT NULL DEFAULT '',
  `org` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `pid` (`pid`),
  KEY `other_nr` (`other_nr`),
  CONSTRAINT `FK_care_person_other_number` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
