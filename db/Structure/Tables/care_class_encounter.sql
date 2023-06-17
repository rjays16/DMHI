
DROP TABLE IF EXISTS `care_class_encounter`;
CREATE TABLE `care_class_encounter` (
  `class_nr` smallint(6) unsigned NOT NULL DEFAULT '0',
  `class_id` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL,
  `LD_var` varchar(25) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `hide_from` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`class_nr`)
);
