
DROP TABLE IF EXISTS `seg_results`;
CREATE TABLE `seg_results` (
  `result_code` int(11) unsigned NOT NULL,
  `result_desc` varchar(80) NOT NULL,
  `area_used` enum('E','A') NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`result_code`)
);
