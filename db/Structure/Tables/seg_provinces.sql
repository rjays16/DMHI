
DROP TABLE IF EXISTS `seg_provinces`;
CREATE TABLE `seg_provinces` (
  `prov_nr` int(11) unsigned NOT NULL,
  `prov_name` varchar(80) NOT NULL,
  `region_nr` int(11) unsigned NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `ordering` smallint(5) DEFAULT '0',
  PRIMARY KEY (`prov_nr`),
  KEY `FK_seg_provinces_region` (`region_nr`),
  KEY `code` (`code`),
  KEY `name` (`prov_name`),
  CONSTRAINT `FK_seg_provinces_region` FOREIGN KEY (`region_nr`) REFERENCES `seg_regions` (`region_nr`) ON UPDATE CASCADE
);
