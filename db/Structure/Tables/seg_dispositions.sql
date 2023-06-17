
DROP TABLE IF EXISTS `seg_dispositions`;
CREATE TABLE `seg_dispositions` (
  `disp_code` int(11) unsigned NOT NULL,
  `disp_desc` varchar(80) NOT NULL,
  `area_used` enum('E','A') DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`disp_code`)
);
