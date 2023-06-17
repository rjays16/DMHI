
DROP TABLE IF EXISTS `care_mode_delivery`;
CREATE TABLE `care_mode_delivery` (
  `nr` smallint(5) unsigned NOT NULL,
  `group_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mode` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
