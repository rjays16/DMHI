
DROP TABLE IF EXISTS `seg_radio_service_groups`;
CREATE TABLE `seg_radio_service_groups` (
  `group_code` varchar(10) NOT NULL,
  `department_nr` int(10) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `other_name` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`group_code`),
  UNIQUE KEY `name` (`name`)
);
