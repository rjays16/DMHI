
DROP TABLE IF EXISTS `care_billing_final`;
CREATE TABLE `care_billing_final` (
  `final_id` tinyint(3) NOT NULL,
  `final_encounter_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `final_bill_no` int(11) DEFAULT NULL,
  `final_date` date DEFAULT NULL,
  `final_total_bill_amount` float(10,2) DEFAULT NULL,
  `final_discount` tinyint(4) DEFAULT NULL,
  `final_total_receipt_amount` float(10,2) DEFAULT NULL,
  `final_amount_due` float(10,2) DEFAULT NULL,
  `final_amount_recieved` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`final_id`),
  KEY `index_final_patnum` (`final_encounter_nr`)
);
