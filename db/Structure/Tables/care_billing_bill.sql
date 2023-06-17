
DROP TABLE IF EXISTS `care_billing_bill`;
CREATE TABLE `care_billing_bill` (
  `bill_bill_no` bigint(20) NOT NULL DEFAULT '0',
  `bill_encounter_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `bill_date_time` date DEFAULT NULL,
  `bill_amount` float(10,2) DEFAULT NULL,
  `bill_outstanding` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`bill_bill_no`),
  KEY `index_bill_patnum` (`bill_encounter_nr`)
);
