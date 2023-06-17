
DROP TABLE IF EXISTS `seg_notifiable_code`;
CREATE TABLE `seg_notifiable_code` (
  `icd_code` varchar(15) NOT NULL,
  `code_illness` varchar(12) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`icd_code`),
  KEY `FK_seg_notifiable_code_illness` (`code_illness`),
  CONSTRAINT `FK_seg_notifiable_code` FOREIGN KEY (`icd_code`) REFERENCES `care_icd10_en` (`diagnosis_code`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_notifiable_code_illness` FOREIGN KEY (`code_illness`) REFERENCES `seg_notifiable_diseases` (`code_illness`) ON DELETE NO ACTION ON UPDATE CASCADE
);
