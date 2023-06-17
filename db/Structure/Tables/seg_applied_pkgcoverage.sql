
DROP TABLE IF EXISTS `seg_applied_pkgcoverage`;
CREATE TABLE `seg_applied_pkgcoverage` (
  `ref_no` varchar(12) NOT NULL,
  `bill_area` enum('AC','MS','HS','OR','D1','D2','D3','D4','XC') NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `priority` smallint(6) NOT NULL,
  `coverage` double(10,4) NOT NULL,
  PRIMARY KEY (`ref_no`,`bill_area`,`hcare_id`),
  KEY `FK_seg_applied_pkgcoverage_hcare_firm` (`hcare_id`),
  CONSTRAINT `FK_seg_applied_pkgcoverage_hcare_firm` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
