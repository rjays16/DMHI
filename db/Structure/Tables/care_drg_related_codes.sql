
DROP TABLE IF EXISTS `care_drg_related_codes`;
CREATE TABLE `care_drg_related_codes` (
  `nr` int(11) NOT NULL,
  `group_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `code` varchar(15) NOT NULL DEFAULT '',
  `code_parent` varchar(15) NOT NULL DEFAULT '',
  `code_type` varchar(15) NOT NULL DEFAULT '',
  `rank` int(11) NOT NULL DEFAULT '0',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(25) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(25) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
