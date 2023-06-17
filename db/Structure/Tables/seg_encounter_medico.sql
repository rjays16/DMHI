
DROP TABLE IF EXISTS `seg_encounter_medico`;
CREATE TABLE `seg_encounter_medico` (
  `encounter_nr` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `medico_cases` varchar(10) NOT NULL,
  `description` text,
  PRIMARY KEY (`encounter_nr`,`pid`,`medico_cases`),
  KEY `FK_seg_encounter_medico` (`medico_cases`),
  KEY `FK_seg_encounter_medico_care_person` (`pid`),
  CONSTRAINT `FK_seg_encounter_medico` FOREIGN KEY (`medico_cases`) REFERENCES `seg_medico_cases` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_medico_care_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
