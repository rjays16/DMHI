
DROP TABLE IF EXISTS `seg_lingap_entries_bill`;
CREATE TABLE `seg_lingap_entries_bill` (
  `entry_id` char(36) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `amount` decimal(18,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`entry_id`),
  KEY `FK_seg_lingap_entries_bill_encounter` (`ref_no`),
  CONSTRAINT `FK_seg_lingap_entries_bill` FOREIGN KEY (`entry_id`) REFERENCES `seg_lingap_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lingap_entries_bill_encounter` FOREIGN KEY (`ref_no`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON UPDATE CASCADE
);
