
DROP TABLE IF EXISTS `seg_lingap_ledger_patient`;
CREATE TABLE `seg_lingap_ledger_patient` (
  `id` int(10) unsigned NOT NULL,
  `control_nr` varchar(10) NOT NULL,
  `entry_date` datetime NOT NULL,
  `pid` varchar(12) NOT NULL,
  `entry_type` enum('deposit','transfer','cancellation','grant','adjustment') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `remarks` tinytext,
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
