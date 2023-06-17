
DROP TABLE IF EXISTS `care_encounter_wellbaby`;
CREATE TABLE `care_encounter_wellbaby` (
  `encounter_nr` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL DEFAULT '0',
  `encounter_date` datetime DEFAULT '0000-00-00 00:00:00',
  `is_discharged` tinyint(1) DEFAULT '0',
  `discharged_date` date DEFAULT NULL,
  `discharged_time` time DEFAULT NULL,
  `is_maygohome` tinyint(1) DEFAULT '0',
  `mgh_setdte` datetime DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`),
  KEY `pid` (`pid`),
  CONSTRAINT `FK_PID` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
