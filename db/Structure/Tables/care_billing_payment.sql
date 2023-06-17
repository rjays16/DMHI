
DROP TABLE IF EXISTS `care_billing_payment`;
CREATE TABLE `care_billing_payment` (
  `payment_id` tinyint(5) NOT NULL,
  `payment_encounter_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_receipt_no` int(11) NOT NULL DEFAULT '0',
  `payment_date` datetime DEFAULT '0000-00-00 00:00:00',
  `payment_cash_amount` float(10,2) DEFAULT '0.00',
  `payment_cheque_no` int(11) DEFAULT '0',
  `payment_cheque_amount` float(10,2) DEFAULT '0.00',
  `payment_creditcard_no` int(25) DEFAULT '0',
  `payment_creditcard_amount` float(10,2) DEFAULT '0.00',
  `payment_amount_total` float(10,2) DEFAULT '0.00',
  PRIMARY KEY (`payment_id`),
  KEY `index_payment_patnum` (`payment_encounter_nr`),
  KEY `index_payment_receipt_no` (`payment_receipt_no`)
);
