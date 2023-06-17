
DROP TABLE IF EXISTS `care_unit_measurement`;
CREATE TABLE `care_unit_measurement` (
  `nr` smallint(5) unsigned NOT NULL,
  `unit_type_nr` smallint(2) unsigned NOT NULL DEFAULT '0',
  `id` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `sytem` varchar(35) NOT NULL DEFAULT '',
  `use_frequency` bigint(20) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  UNIQUE KEY `id` (`id`)
);
