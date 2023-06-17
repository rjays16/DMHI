
DROP TABLE IF EXISTS `care_type_cause_opdelay`;
CREATE TABLE `care_type_cause_opdelay` (
  `type_nr` smallint(5) unsigned NOT NULL,
  `type` varchar(35) NOT NULL DEFAULT '',
  `cause` varchar(255) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type_nr`),
  KEY `type` (`type`)
);
