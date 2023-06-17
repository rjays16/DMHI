
DROP TABLE IF EXISTS `seg_lab_service_groups`;
CREATE TABLE `seg_lab_service_groups` (
  `group_code` varchar(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `other_name` varchar(50) DEFAULT NULL,
  `in_lis` tinyint(1) DEFAULT '0',
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `dept_nr` int(11) DEFAULT '156',
  PRIMARY KEY (`group_code`),
  UNIQUE KEY `name` (`name`)
);
