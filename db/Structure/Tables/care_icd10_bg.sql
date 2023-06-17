
DROP TABLE IF EXISTS `care_icd10_bg`;
CREATE TABLE `care_icd10_bg` (
  `diagnosis_code` varchar(12) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `class_sub` varchar(5) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `inclusive` text NOT NULL,
  `exclusive` text NOT NULL,
  `notes` text NOT NULL,
  `std_code` char(1) NOT NULL DEFAULT '',
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  `extra_codes` text NOT NULL,
  `extra_subclass` text NOT NULL,
  PRIMARY KEY (`diagnosis_code`),
  KEY `diagnosis_code` (`diagnosis_code`)
);
