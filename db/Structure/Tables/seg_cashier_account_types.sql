
DROP TABLE IF EXISTS `seg_cashier_account_types`;
CREATE TABLE `seg_cashier_account_types` (
  `type_id` int(11) NOT NULL,
  `name_short` varchar(20) NOT NULL,
  `name_long` varchar(40) NOT NULL DEFAULT '',
  `lockflag` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type_id`)
);
