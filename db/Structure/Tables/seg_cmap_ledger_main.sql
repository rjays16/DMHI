
DROP TABLE IF EXISTS `seg_cmap_ledger_main`;
CREATE TABLE `seg_cmap_ledger_main` (
  `entry_id` int(11) unsigned NOT NULL,
  `control_nr` varchar(10) NOT NULL,
  `entry_date` datetime NOT NULL,
  `account_nr` int(11) unsigned NOT NULL,
  `associated_id` varchar(20) NOT NULL,
  `entry_type` enum('allotment','transfer','grant') NOT NULL DEFAULT 'allotment',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount_in_words` tinytext NOT NULL,
  `remarks` tinytext NOT NULL,
  `history` text NOT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`entry_id`)
);
