
DROP TABLE IF EXISTS `seg_icd_10_mortality_condensed_tabular`;
CREATE TABLE `seg_icd_10_mortality_condensed_tabular` (
  `diagnosis_code` varchar(15) NOT NULL,
  `tab_code` varchar(10) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`diagnosis_code`)
);
