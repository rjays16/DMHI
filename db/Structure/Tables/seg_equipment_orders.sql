
DROP TABLE IF EXISTS `seg_equipment_orders`;
CREATE TABLE `seg_equipment_orders` (
  `refno` varchar(10) NOT NULL,
  `area` varchar(5) DEFAULT NULL,
  `request_refno` varchar(10) DEFAULT NULL,
  `amount_due` double DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `patient_name` varchar(200) DEFAULT NULL,
  `patient_address` varchar(250) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `is_cash` tinyint(4) DEFAULT NULL,
  `is_sc` tinyint(4) DEFAULT NULL,
  `history` text,
  `created_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_id` varchar(35) DEFAULT NULL,
  `modified_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
