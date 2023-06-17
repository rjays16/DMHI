
DROP TABLE IF EXISTS `seg_billingcomputed_discount`;
CREATE TABLE `seg_billingcomputed_discount` (
  `bill_nr` varchar(12) NOT NULL,
  `total_acc_discount` double(20,4) NOT NULL,
  `total_med_discount` double(20,4) NOT NULL,
  `total_sup_discount` double(20,4) NOT NULL,
  `total_srv_discount` double(20,4) NOT NULL,
  `total_ops_discount` double(20,4) NOT NULL,
  `total_d1_discount` double(20,4) NOT NULL,
  `total_d2_discount` double(20,4) NOT NULL,
  `total_d3_discount` double(20,4) NOT NULL,
  `total_d4_discount` double(20,4) NOT NULL,
  `total_msc_discount` double(20,4) NOT NULL,
  `hospital_income_discount` double(20,4) NOT NULL,
  `professional_income_discount` double(20,4) NOT NULL,
  PRIMARY KEY (`bill_nr`),
  CONSTRAINT `FK_seg_billingcomputed_discount_encounter` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
