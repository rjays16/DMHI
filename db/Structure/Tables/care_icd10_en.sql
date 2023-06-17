
DROP TABLE IF EXISTS `care_icd10_en`;
CREATE TABLE `care_icd10_en` (
  `diagnosis_code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `class_sub` varchar(5) DEFAULT '',
  `type` varchar(10) DEFAULT '',
  `inclusive` text,
  `exclusive` text,
  `notes` text,
  `std_code` char(1) DEFAULT '',
  `sub_level` tinyint(4) DEFAULT '0',
  `remarks` text,
  `extra_codes` text,
  `extra_subclass` text,
  PRIMARY KEY (`diagnosis_code`)
);
