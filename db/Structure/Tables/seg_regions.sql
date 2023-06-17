
DROP TABLE IF EXISTS `seg_regions`;
CREATE TABLE `seg_regions` (
  `region_nr` int(11) unsigned NOT NULL,
  `region_name` varchar(80) NOT NULL,
  `region_desc` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `ordering` smallint(5) DEFAULT '0',
  PRIMARY KEY (`region_nr`),
  KEY `code` (`code`),
  KEY `name` (`region_name`)
);
