
DROP TABLE IF EXISTS `seg_caseratepkgdist`;
CREATE TABLE `seg_caseratepkgdist` (
  `dist_id` char(36) NOT NULL,
  `effect_date` date NOT NULL DEFAULT '0000-00-00',
  `dist_hosp` double(10,3) NOT NULL DEFAULT '0.000',
  `dist_pfdaily` double(10,3) NOT NULL DEFAULT '0.000',
  `dist_pfsurgeon` double(10,3) NOT NULL DEFAULT '0.000',
  `dist_pfanesth` double(10,3) NOT NULL DEFAULT '0.000',
  `case_type` enum('M','S') NOT NULL DEFAULT 'M',
  PRIMARY KEY (`dist_id`)
);
