
DROP TABLE IF EXISTS `care_test_group`;
CREATE TABLE `care_test_group` (
  `nr` smallint(5) unsigned NOT NULL,
  `group_id` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `sort_nr` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`,`group_id`),
  UNIQUE KEY `group_id` (`group_id`)
);
