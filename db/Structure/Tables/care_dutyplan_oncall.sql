
DROP TABLE IF EXISTS `care_dutyplan_oncall`;
CREATE TABLE `care_dutyplan_oncall` (
  `nr` bigint(20) unsigned NOT NULL,
  `dept_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `role_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `year` year(4) NOT NULL DEFAULT '0000',
  `month` char(2) NOT NULL DEFAULT '',
  `duty_1_txt` text NOT NULL,
  `duty_2_txt` text NOT NULL,
  `duty_1_pnr` text NOT NULL,
  `duty_2_pnr` text NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `dept_nr` (`dept_nr`)
);
