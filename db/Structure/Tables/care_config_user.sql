
DROP TABLE IF EXISTS `care_config_user`;
CREATE TABLE `care_config_user` (
  `user_id` varchar(100) NOT NULL DEFAULT '',
  `serial_config_data` text NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`)
);
