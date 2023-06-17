
DROP TABLE IF EXISTS `care_tech_repair_done`;
CREATE TABLE `care_tech_repair_done` (
  `batch_nr` int(11) NOT NULL,
  `dept` varchar(15) DEFAULT NULL,
  `dept_nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `job_id` varchar(15) NOT NULL DEFAULT '0',
  `job` text NOT NULL,
  `reporter` varchar(25) NOT NULL DEFAULT '',
  `id` varchar(15) NOT NULL DEFAULT '',
  `tdate` date NOT NULL DEFAULT '0000-00-00',
  `ttime` time NOT NULL DEFAULT '00:00:00',
  `tid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `d_idx` varchar(8) NOT NULL DEFAULT '',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`,`dept_nr`)
);
