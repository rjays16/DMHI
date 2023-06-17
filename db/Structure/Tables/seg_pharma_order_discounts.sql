
DROP TABLE IF EXISTS `seg_pharma_order_discounts`;
CREATE TABLE `seg_pharma_order_discounts` (
  `refno` varchar(10) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  PRIMARY KEY (`refno`,`discountid`),
  KEY `FK_seg_pharma_order_discounts_discount` (`discountid`),
  CONSTRAINT `FK_seg_pharma_order_discounts` FOREIGN KEY (`refno`) REFERENCES `seg_pharma_orders` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_order_discounts_discount` FOREIGN KEY (`discountid`) REFERENCES `seg_discount` (`discountid`) ON DELETE CASCADE ON UPDATE CASCADE
);
