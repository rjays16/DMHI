
DROP TABLE IF EXISTS `seg_icd_10_morphology`;
CREATE TABLE `seg_icd_10_morphology` (
  `morpho_code` varchar(10) NOT NULL,
  `name` text,
  `group_desc` text,
  `start_range` varchar(5) DEFAULT NULL,
  `end_range` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`morpho_code`)
);
