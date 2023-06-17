
DROP TABLE IF EXISTS `seg_cmap_main_ledger`;
CREATE TABLE `seg_cmap_main_ledger` (
  `id` char(36) NOT NULL,
  `control_nr` varchar(25) NOT NULL,
  `entry_date` datetime NOT NULL,
  `cmap_account` smallint(5) unsigned NOT NULL,
  `associated_id` varchar(20) NOT NULL,
  `entry_type` enum('allotment','referral','grant') NOT NULL DEFAULT 'allotment',
  `amount` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `remarks` tinytext NOT NULL,
  `history` text NOT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
