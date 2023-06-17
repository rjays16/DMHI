
DROP TABLE IF EXISTS `seg_lingap_walkin_entries`;
CREATE TABLE `seg_lingap_walkin_entries` (
  `id` char(36) NOT NULL,
  `control_nr` varchar(15) NOT NULL,
  `entry_date` datetime NOT NULL,
  `pid` varchar(12) NOT NULL,
  `name` varchar(200) NOT NULL,
  `address` tinytext NOT NULL,
  `remarks` tinytext NOT NULL,
  `is_advance` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `history` text NOT NULL,
  `create_id` varchar(15) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(15) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_seg_lingap_walkin_entries` (`pid`),
  CONSTRAINT `FK_seg_lingap_walkin_entries` FOREIGN KEY (`pid`) REFERENCES `seg_walkin` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
