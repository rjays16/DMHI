
DROP TABLE IF EXISTS `seg_barangays`;
CREATE TABLE `seg_barangays` (
  `brgy_nr` int(11) unsigned NOT NULL,
  `brgy_name` varchar(80) NOT NULL,
  `mun_nr` int(11) unsigned NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `ordering` smallint(5) DEFAULT '0',
  PRIMARY KEY (`brgy_nr`),
  KEY `FK_seg_barangays_1` (`mun_nr`),
  KEY `code` (`code`),
  KEY `name` (`brgy_name`),
  CONSTRAINT `FK_seg_barangays_1` FOREIGN KEY (`mun_nr`) REFERENCES `seg_municity` (`mun_nr`) ON UPDATE CASCADE
);
