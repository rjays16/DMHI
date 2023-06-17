
DROP TABLE IF EXISTS `seg_billing_coverage`;
CREATE TABLE `seg_billing_coverage` (
  `bill_nr` varchar(12) NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `total_services_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_acc_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_med_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_sup_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_srv_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_ops_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_d1_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_d2_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_d3_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_d4_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `total_msc_coverage` decimal(20,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`bill_nr`,`hcare_id`),
  KEY `FK_seg_billing_coverage_insurance` (`hcare_id`),
  CONSTRAINT `FK_seg_billing_coverage_billing` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_billing_coverage_insurance` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
