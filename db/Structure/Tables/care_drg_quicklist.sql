
DROP TABLE IF EXISTS `care_drg_quicklist`;
CREATE TABLE `care_drg_quicklist` (
  `nr` int(11) NOT NULL,
  `code` varchar(25) NOT NULL DEFAULT '',
  `code_parent` varchar(25) NOT NULL DEFAULT '',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `qlist_type` varchar(25) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(25) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(25) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
