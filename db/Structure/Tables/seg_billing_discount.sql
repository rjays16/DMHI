
DROP TABLE IF EXISTS `seg_billing_discount`;
CREATE TABLE `seg_billing_discount` (
  `bill_nr` varchar(12) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  `discount` decimal(10,8) NOT NULL,
  `discount_amnt` double(10,2) DEFAULT NULL,
  PRIMARY KEY (`bill_nr`,`discountid`),
  KEY `FK_seg_billing_discount_discount` (`discountid`),
  CONSTRAINT `FK_seg_billing_discount_billing` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_billing_discount_discount` FOREIGN KEY (`discountid`) REFERENCES `seg_discount` (`discountid`) ON UPDATE CASCADE
);
