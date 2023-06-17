
DROP TABLE IF EXISTS `care_tech_repair_job`;
CREATE TABLE `care_tech_repair_job` (
  `batch_nr` tinyint(4) NOT NULL,
  `dept` varchar(15) NOT NULL DEFAULT '',
  `job` text NOT NULL,
  `reporter` varchar(25) NOT NULL DEFAULT '',
  `id` varchar(15) NOT NULL DEFAULT '',
  `tphone` varchar(30) NOT NULL DEFAULT '',
  `tdate` date NOT NULL DEFAULT '0000-00-00',
  `ttime` time NOT NULL DEFAULT '00:00:00',
  `tid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `done` tinyint(1) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `seenby` varchar(25) NOT NULL DEFAULT '',
  `sstamp` varchar(16) NOT NULL DEFAULT '',
  `doneby` varchar(25) NOT NULL DEFAULT '',
  `dstamp` varchar(16) NOT NULL DEFAULT '',
  `d_idx` varchar(8) NOT NULL DEFAULT '',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `batch_nr` (`batch_nr`)
);
