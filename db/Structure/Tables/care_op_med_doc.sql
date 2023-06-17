
DROP TABLE IF EXISTS `care_op_med_doc`;
CREATE TABLE `care_op_med_doc` (
  `nr` bigint(20) unsigned NOT NULL,
  `op_date` varchar(12) NOT NULL DEFAULT '',
  `operator` tinytext NOT NULL,
  `encounter_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `diagnosis` text NOT NULL,
  `localize` text NOT NULL,
  `therapy` text NOT NULL,
  `special` text NOT NULL,
  `class_s` tinyint(4) NOT NULL DEFAULT '0',
  `class_m` tinyint(4) NOT NULL DEFAULT '0',
  `class_l` tinyint(4) NOT NULL DEFAULT '0',
  `op_start` varchar(8) NOT NULL DEFAULT '',
  `op_end` varchar(8) NOT NULL DEFAULT '',
  `scrub_nurse` varchar(70) NOT NULL DEFAULT '',
  `op_room` varchar(10) NOT NULL DEFAULT '',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`)
);
