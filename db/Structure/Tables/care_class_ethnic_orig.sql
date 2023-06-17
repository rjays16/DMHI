
DROP TABLE IF EXISTS `care_class_ethnic_orig`;
CREATE TABLE `care_class_ethnic_orig` (
  `nr` smallint(5) unsigned NOT NULL,
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
