
DROP TABLE IF EXISTS `seg_areas`;
CREATE TABLE `seg_areas` (
  `area_code` varchar(10) NOT NULL DEFAULT '',
  `area_name` varchar(80) NOT NULL,
  `allow_socialized` tinyint(1) NOT NULL DEFAULT '0',
  `lockflag` tinyint(1) NOT NULL DEFAULT '0',
  `dept_nr` mediumint(8) unsigned NOT NULL,
  `ward_nr` smallint(5) unsigned DEFAULT NULL,
  `has_stocks` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  KEY `FK_seg_areas_care_department` (`dept_nr`),
  CONSTRAINT `FK_seg_areas_care_department` FOREIGN KEY (`dept_nr`) REFERENCES `care_department` (`nr`) ON UPDATE CASCADE
);
