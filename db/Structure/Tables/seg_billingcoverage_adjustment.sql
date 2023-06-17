
DROP TABLE IF EXISTS `seg_billingcoverage_adjustment`;
CREATE TABLE `seg_billingcoverage_adjustment` (
  `ref_no` varchar(12) NOT NULL,
  `bill_area` enum('AC','MS','HS','OR','D1','D2','D3','D4','XC') NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  `coverage` double(10,4) NOT NULL,
  PRIMARY KEY (`ref_no`,`bill_area`,`hcare_id`),
  KEY `FK_seg_billingcoverage_adjustment_1` (`hcare_id`),
  CONSTRAINT `FK_seg_billingcoverage_adjustment_1` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
