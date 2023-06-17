
DROP TABLE IF EXISTS `care_person_insurance`;
CREATE TABLE `care_person_insurance` (
  `pid` varchar(12) NOT NULL DEFAULT '0',
  `hcare_id` int(8) NOT NULL,
  `insurance_nr` varchar(25) NOT NULL DEFAULT '0',
  `is_principal` tinyint(1) NOT NULL DEFAULT '0',
  `class_nr` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `is_void` tinyint(1) NOT NULL DEFAULT '0',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pid`,`hcare_id`),
  KEY `index_insurancenr` (`insurance_nr`),
  KEY `index_hcareid` (`hcare_id`),
  KEY `index_pid` (`pid`),
  CONSTRAINT `FK_care_person_insurance` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
