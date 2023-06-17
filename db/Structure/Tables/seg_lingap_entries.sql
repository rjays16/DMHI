
DROP TABLE IF EXISTS `seg_lingap_entries`;
CREATE TABLE `seg_lingap_entries` (
  `id` char(36) NOT NULL,
  `control_nr` varchar(15) DEFAULT NULL,
  `entry_date` datetime NOT NULL,
  `ss_nr` varchar(10) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `walkin_pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `remarks` tinytext,
  `is_advance` tinyint(1) NOT NULL DEFAULT '0',
  `history` text,
  `create_id` varchar(15) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(15) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_seg_lingap_entries_ss` (`ss_nr`),
  KEY `FK_seg_lingap_entries_person` (`pid`),
  KEY `FK_seg_lingap_entries_walkin` (`walkin_pid`),
  CONSTRAINT `FK_seg_lingap_entries_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lingap_entries_ss` FOREIGN KEY (`ss_nr`) REFERENCES `seg_social_lingap` (`control_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lingap_entries_walkin` FOREIGN KEY (`walkin_pid`) REFERENCES `seg_walkin` (`pid`) ON UPDATE CASCADE
);
