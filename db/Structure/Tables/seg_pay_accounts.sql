
DROP TABLE IF EXISTS `seg_pay_accounts`;
CREATE TABLE `seg_pay_accounts` (
  `id` char(20) NOT NULL,
  `short_name` varchar(20) NOT NULL,
  `formal_name` varchar(50) NOT NULL,
  `description` tinytext,
  `priority` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
