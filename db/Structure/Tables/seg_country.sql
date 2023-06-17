
DROP TABLE IF EXISTS `seg_country`;
CREATE TABLE `seg_country` (
  `country_code` varchar(3) NOT NULL,
  `country_name` varchar(150) NOT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`country_code`)
);
