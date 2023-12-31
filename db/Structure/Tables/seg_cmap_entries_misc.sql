
DROP TABLE IF EXISTS `seg_cmap_entries_misc`;
CREATE TABLE `seg_cmap_entries_misc` (
  `id` char(36) NOT NULL,
  `referral_id` char(36) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `walkin_pid` varchar(12) DEFAULT NULL,
  `service_code` varchar(15) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `remarks` tinytext NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`ref_no`,`service_code`,`referral_id`,`entry_no`),
  KEY `FK_seg_cmap_entries_ld` (`ref_no`,`service_code`),
  KEY `FK_seg_cmap_entries_ld_person` (`pid`),
  KEY `FK_seg_cmap_entries_others` (`service_code`),
  KEY `FK_seg_cmap_entries_misc_referral` (`referral_id`),
  KEY `FK_seg_cmap_entries_misc_creator` (`create_id`),
  KEY `FK_seg_cmap_entries_misc` (`ref_no`,`service_code`,`entry_no`),
  KEY `FK_seg_cmap_entries_misc_walkin` (`walkin_pid`),
  CONSTRAINT `FK_seg_cmap_entries_misc` FOREIGN KEY (`ref_no`, `service_code`, `entry_no`) REFERENCES `seg_misc_service_details` (`refno`, `service_code`, `entry_no`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_misc_creator` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_misc_referral` FOREIGN KEY (`referral_id`) REFERENCES `seg_cmap_referrals` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_misc_walkin` FOREIGN KEY (`walkin_pid`) REFERENCES `seg_walkin` (`pid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_others_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
