
DROP TABLE IF EXISTS `seg_cmap_entries_bill`;
CREATE TABLE `seg_cmap_entries_bill` (
  `id` char(36) NOT NULL,
  `referral_id` char(36) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `walkin_pid` varchar(12) DEFAULT NULL,
  `service_code` varchar(15) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` decimal(10,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `remarks` tinytext,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`ref_no`,`service_code`,`referral_id`),
  KEY `FK_seg_cmap_entries_fb_person` (`pid`),
  KEY `FK_seg_cmap_entries_bill` (`referral_id`),
  KEY `FK_seg_cmap_entries_bill_creator` (`create_id`),
  KEY `FK_seg_cmap_entries_bill_modifier` (`modify_id`),
  KEY `FK_seg_cmap_entries_bill_walkin` (`walkin_pid`),
  CONSTRAINT `FK_seg_cmap_entries_bill` FOREIGN KEY (`referral_id`) REFERENCES `seg_cmap_referrals` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_bill_creator` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_bill_modifier` FOREIGN KEY (`modify_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_bill_walkin` FOREIGN KEY (`walkin_pid`) REFERENCES `seg_walkin` (`pid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_fb_billing` FOREIGN KEY (`ref_no`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cmap_entries_fb_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
