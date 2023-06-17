
DROP TABLE IF EXISTS `seg_pay`;
CREATE TABLE `seg_pay` (
  `or_no` varchar(12) NOT NULL,
  `account_type` int(11) DEFAULT NULL,
  `cancel_date` datetime DEFAULT NULL,
  `cancelled_by` varchar(35) NOT NULL,
  `or_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `or_name` varchar(200) DEFAULT NULL,
  `or_address` varchar(300) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `company_id` varchar(12) DEFAULT NULL,
  `amount_tendered` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `amount_due` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `remarks` varchar(300) DEFAULT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`or_no`),
  KEY `FK_seg_pay` (`account_type`),
  KEY `FK_seg_pay_person` (`pid`),
  KEY `createid_index` (`create_id`),
  KEY `or_date_index` (`or_date`),
  KEY `orname_index` (`or_name`),
  CONSTRAINT `FK_seg_pay` FOREIGN KEY (`account_type`) REFERENCES `seg_cashier_account_subtypes` (`type_id`) ON UPDATE CASCADE
);
