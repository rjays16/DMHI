
DROP TABLE IF EXISTS `care_complication`;
CREATE TABLE `care_complication` (
  `nr` int(10) unsigned NOT NULL,
  `group_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `code` varchar(25) DEFAULT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
