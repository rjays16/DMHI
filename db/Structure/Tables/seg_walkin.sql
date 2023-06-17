
DROP TABLE IF EXISTS `seg_walkin`;
CREATE TABLE `seg_walkin` (
  `pid` varchar(12) NOT NULL,
  `sex` enum('M','F') NOT NULL DEFAULT 'M',
  `name_last` varchar(50) NOT NULL,
  `name_first` varchar(50) NOT NULL,
  `name_middle` varchar(50) DEFAULT NULL,
  `date_birth` date NOT NULL,
  `address` varchar(150) NOT NULL,
  `last_transaction` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` varchar(35) NOT NULL,
  `history` text NOT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pid`),
  KEY `idx_name_last` (`name_last`),
  KEY `idx_name_first` (`name_first`),
  KEY `idx_name_last_name_first` (`name_last`,`name_first`)
);
