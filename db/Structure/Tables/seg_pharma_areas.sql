
DROP TABLE IF EXISTS `seg_pharma_areas`;
CREATE TABLE `seg_pharma_areas` (
  `area_code` varchar(10) NOT NULL,
  `area_name` varchar(50) NOT NULL,
  `allow_socialized` tinyint(1) NOT NULL DEFAULT '0',
  `lockflag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`)
);
