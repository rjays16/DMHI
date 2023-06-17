
DROP TABLE IF EXISTS `seg_billing_caserate`;
CREATE TABLE `seg_billing_caserate` (
  `bill_nr` varchar(12) NOT NULL,
  `package_id` varchar(12) NOT NULL,
  `rate_type` tinyint(1) NOT NULL,
  `amount` decimal(20,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`bill_nr`,`package_id`),
  KEY `indx_package_id` (`package_id`),
  CONSTRAINT `FK_bill_nr_caserate` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
