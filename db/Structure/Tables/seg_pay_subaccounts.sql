
DROP TABLE IF EXISTS `seg_pay_subaccounts`;
CREATE TABLE `seg_pay_subaccounts` (
  `id` char(20) NOT NULL,
  `parent_account` char(20) NOT NULL,
  `short_name` varchar(20) NOT NULL,
  `formal_name` varchar(50) NOT NULL,
  `description` tinytext,
  `priority` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_pay_subaccounts_parent_account` (`parent_account`),
  CONSTRAINT `FK_seg_pay_subaccounts_parent_account` FOREIGN KEY (`parent_account`) REFERENCES `seg_pay_accounts` (`id`) ON UPDATE CASCADE
);
