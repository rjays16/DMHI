
DROP TABLE IF EXISTS `seg_lingap_entries_misc`;
CREATE TABLE `seg_lingap_entries_misc` (
  `entry_id` char(36) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` decimal(18,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(18,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`ref_no`,`service_code`),
  KEY `FK_seg_lingap_entries_misc` (`entry_id`),
  KEY `FK_seg_lingap_entries_misc_items` (`ref_no`,`service_code`,`entry_no`),
  CONSTRAINT `FK_seg_lingap_entries_misc` FOREIGN KEY (`entry_id`) REFERENCES `seg_lingap_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lingap_entries_misc_items` FOREIGN KEY (`ref_no`, `service_code`, `entry_no`) REFERENCES `seg_misc_service_details` (`refno`, `service_code`, `entry_no`) ON UPDATE CASCADE
);
