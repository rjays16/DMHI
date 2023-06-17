
DROP TABLE IF EXISTS `care_type_ethnic_orig`;
CREATE TABLE `care_type_ethnic_orig` (
  `nr` smallint(5) unsigned NOT NULL,
  `class_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `use_frequency` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'burn added: August 23, 2006',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `type` (`class_nr`)
);
