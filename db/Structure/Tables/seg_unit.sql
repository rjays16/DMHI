
DROP TABLE IF EXISTS `seg_unit`;
CREATE TABLE `seg_unit` (
  `unit_id` int(10) unsigned NOT NULL,
  `unit_code` varchar(4) NOT NULL,
  `unit_name` varchar(25) NOT NULL,
  `is_unit_per_pc` tinyint(1) DEFAULT NULL,
  `unit_desc` varchar(80) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`unit_id`)
);
