
DROP TABLE IF EXISTS `seg_industrial_bill_discount`;
CREATE TABLE `seg_industrial_bill_discount` (
  `bill_nr` varchar(12) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `grant_dte` datetime DEFAULT NULL,
  `personell_nr` int(11) DEFAULT NULL,
  PRIMARY KEY (`bill_nr`)
);
