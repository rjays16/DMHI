
DROP TABLE IF EXISTS `seg_icd_10_tabno`;
CREATE TABLE `seg_icd_10_tabno` (
  `tab_id` varchar(10) NOT NULL,
  `type` enum('mortality','morbidity') NOT NULL,
  `icd10_code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `is_bold` tinyint(1) NOT NULL,
  PRIMARY KEY (`tab_id`,`type`,`icd10_code`)
);
