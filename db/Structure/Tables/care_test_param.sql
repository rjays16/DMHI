
DROP TABLE IF EXISTS `care_test_param`;
CREATE TABLE `care_test_param` (
  `nr` smallint(5) unsigned NOT NULL,
  `group_id` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `id` varchar(10) NOT NULL DEFAULT '',
  `msr_unit` varchar(15) NOT NULL DEFAULT '',
  `median` varchar(20) DEFAULT NULL,
  `hi_bound` varchar(20) DEFAULT NULL,
  `lo_bound` varchar(20) DEFAULT NULL,
  `hi_critical` varchar(20) DEFAULT NULL,
  `lo_critical` varchar(20) DEFAULT NULL,
  `hi_toxic` varchar(20) DEFAULT NULL,
  `lo_toxic` varchar(20) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`,`group_id`)
);
