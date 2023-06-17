
DROP TABLE IF EXISTS `seg_cashier_account_subtypes`;
CREATE TABLE `seg_cashier_account_subtypes` (
  `type_id` int(11) NOT NULL,
  `name_short` varchar(20) NOT NULL,
  `name_long` varchar(35) NOT NULL,
  `involves_patient` smallint(1) NOT NULL DEFAULT '0',
  `lockflag` smallint(1) NOT NULL DEFAULT '0',
  `parent_type` int(11) DEFAULT NULL,
  `billing_related` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indicator if subaccount is used by billing dept. or not.',
  `pay_account` char(20) NOT NULL DEFAULT 'hi' COMMENT 'hi',
  PRIMARY KEY (`type_id`),
  KEY `FK_seg_cashier_account_subtypes` (`parent_type`),
  KEY `FK_seg_cashier_account_subtypes_accountmap` (`pay_account`),
  CONSTRAINT `FK_seg_cashier_account_subtypes` FOREIGN KEY (`parent_type`) REFERENCES `seg_cashier_account_types` (`type_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_cashier_account_subtypes_accountmap` FOREIGN KEY (`pay_account`) REFERENCES `seg_pay_subaccounts` (`id`) ON UPDATE CASCADE
);
