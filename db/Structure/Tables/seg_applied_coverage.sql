
DROP TABLE IF EXISTS `seg_applied_coverage`;
CREATE TABLE `seg_applied_coverage` (
  `ref_no` varchar(12) NOT NULL,
  `source` enum('L','R','M','S','E','O') NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `coverage` decimal(10,2) NOT NULL DEFAULT '0.00',
  `history` text,
  PRIMARY KEY (`ref_no`,`source`,`item_code`,`hcare_id`),
  KEY `FK_seg_applied_coverage_insurance` (`hcare_id`),
  KEY `refnoindex` (`ref_no`),
  KEY `itemcodeindex` (`item_code`),
  KEY `source_index` (`source`),
  CONSTRAINT `FK_seg_applied_coverage_insurance` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
