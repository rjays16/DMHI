
DROP TABLE IF EXISTS `seg_doctors_diagnosis`;
CREATE TABLE `seg_doctors_diagnosis` (
  `icd_code` varchar(15) NOT NULL,
  `personell_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`icd_code`,`personell_nr`,`encounter_nr`),
  KEY `FK_seg_doctors_diagnosis_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_doctors_diagnosis_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_doctors_diagnosis_icd` FOREIGN KEY (`icd_code`) REFERENCES `care_icd10_en` (`diagnosis_code`) ON UPDATE CASCADE
);
