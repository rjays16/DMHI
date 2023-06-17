
DROP TABLE IF EXISTS `seg_cmap_ledger_patient`;
CREATE TABLE `seg_cmap_ledger_patient` (
  `entry_id` int(11) unsigned NOT NULL,
  `control_nr` varchar(10) NOT NULL,
  `entry_date` datetime NOT NULL,
  `pid` varchar(12) NOT NULL,
  `associated_id` varchar(20) NOT NULL,
  `entry_type` enum('deposit','transfer','grant','adjustment') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `remarks` tinytext NOT NULL,
  `history` text NOT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`entry_id`),
  KEY `FK_seg_cmap_ledger_patient` (`pid`),
  CONSTRAINT `FK_seg_cmap_ledger_patient` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
