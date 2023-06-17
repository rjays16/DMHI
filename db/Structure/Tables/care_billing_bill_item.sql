
DROP TABLE IF EXISTS `care_billing_bill_item`;
CREATE TABLE `care_billing_bill_item` (
  `bill_item_id` int(11) NOT NULL,
  `bill_item_encounter_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `bill_item_code` varchar(5) DEFAULT NULL,
  `bill_item_unit_cost` float(10,2) DEFAULT '0.00',
  `bill_item_units` tinyint(4) DEFAULT NULL,
  `bill_item_amount` float(10,2) DEFAULT NULL,
  `bill_item_date` datetime DEFAULT NULL,
  `bill_item_status` enum('0','1') DEFAULT '0',
  `bill_item_bill_no` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bill_item_id`),
  KEY `index_bill_item_patnum` (`bill_item_encounter_nr`),
  KEY `index_bill_item_bill_no` (`bill_item_bill_no`)
);
