
DROP TABLE IF EXISTS `seg_cmap_accounts`;
CREATE TABLE `seg_cmap_accounts` (
  `account_nr` smallint(11) unsigned NOT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `account_address` varchar(200) DEFAULT NULL,
  `running_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_nr`)
);
