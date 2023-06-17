
DROP TABLE IF EXISTS `care_tech_questions`;
CREATE TABLE `care_tech_questions` (
  `batch_nr` int(11) NOT NULL,
  `dept` varchar(60) NOT NULL DEFAULT '',
  `query` text NOT NULL,
  `inquirer` varchar(25) NOT NULL DEFAULT '',
  `tphone` varchar(30) NOT NULL DEFAULT '',
  `tdate` date NOT NULL DEFAULT '0000-00-00',
  `ttime` time NOT NULL DEFAULT '00:00:00',
  `tid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply` text NOT NULL,
  `answered` tinyint(1) NOT NULL DEFAULT '0',
  `ansby` varchar(25) NOT NULL DEFAULT '',
  `astamp` varchar(16) NOT NULL DEFAULT '',
  `archive` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(15) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`batch_nr`),
  KEY `batch_nr` (`batch_nr`)
);
