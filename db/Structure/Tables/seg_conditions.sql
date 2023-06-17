
DROP TABLE IF EXISTS `seg_conditions`;
CREATE TABLE `seg_conditions` (
  `cond_code` int(11) unsigned NOT NULL,
  `cond_desc` varchar(80) NOT NULL,
  `area_used` enum('E','A') DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`cond_code`)
);
