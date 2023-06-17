
DROP TABLE IF EXISTS `care_config_global`;
CREATE TABLE `care_config_global` (
  `type` varchar(60) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type`)
);
