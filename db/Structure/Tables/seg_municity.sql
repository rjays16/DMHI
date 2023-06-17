
DROP TABLE IF EXISTS `seg_municity`;
CREATE TABLE `seg_municity` (
  `mun_nr` int(11) unsigned NOT NULL,
  `mun_name` varchar(80) NOT NULL,
  `zipcode` varchar(6) NOT NULL,
  `prov_nr` int(11) unsigned NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `ordering` smallint(5) DEFAULT '0',
  PRIMARY KEY (`mun_nr`),
  KEY `FK_seg_municity_prov` (`prov_nr`),
  KEY `code` (`code`),
  KEY `name` (`mun_name`),
  CONSTRAINT `FK_seg_municity_prov` FOREIGN KEY (`prov_nr`) REFERENCES `seg_provinces` (`prov_nr`) ON UPDATE CASCADE
);
