
DROP TABLE IF EXISTS `seg_pay_discount`;
CREATE TABLE `seg_pay_discount` (
  `or_no` varchar(12) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  `discountdesc` varchar(50) NOT NULL,
  `discount` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`or_no`,`discountid`),
  KEY `FK_seg_pay_discount_discount` (`discountid`),
  CONSTRAINT `FK_seg_pay_discount_discount` FOREIGN KEY (`discountid`) REFERENCES `seg_discount` (`discountid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pay_discount_payhdr` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE
);
