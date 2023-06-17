
DROP TABLE IF EXISTS `seg_encounter_icd`;
CREATE TABLE `seg_encounter_icd` (
  `encounter_nr` varchar(12) NOT NULL,
  `diagnosis_code` varchar(15) NOT NULL,
  `status` varchar(25) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`diagnosis_code`),
  KEY `FK_seg_encounter_icd_2` (`diagnosis_code`),
  CONSTRAINT `FK_seg_encounter_icd` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_icd_2` FOREIGN KEY (`diagnosis_code`) REFERENCES `care_icd10_en` (`diagnosis_code`) ON DELETE CASCADE ON UPDATE CASCADE
);
