
DROP TABLE IF EXISTS `care_billing_archive`;
CREATE TABLE `care_billing_archive` (
  `bill_no` bigint(20) NOT NULL DEFAULT '0',
  `encounter_nr` int(10) NOT NULL DEFAULT '0',
  `patient_name` tinytext NOT NULL,
  `vorname` varchar(35) NOT NULL DEFAULT '0',
  `bill_date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bill_amt` double(16,4) NOT NULL DEFAULT '0.0000',
  `payment_date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment_mode` text NOT NULL,
  `cheque_no` varchar(10) NOT NULL DEFAULT '0',
  `creditcard_no` varchar(10) NOT NULL DEFAULT '0',
  `paid_by` varchar(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bill_no`)
);
