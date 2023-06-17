
DROP TABLE IF EXISTS `seg_pharma_rdiscount`;
CREATE TABLE `seg_pharma_rdiscount` (
  `refno` varchar(20) NOT NULL,
  `discountid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`refno`,`discountid`),
  KEY `FK_seg_pharma_rdiscount` (`discountid`),
  CONSTRAINT `seg_pharma_rdiscount_ibfk_1` FOREIGN KEY (`refno`) REFERENCES `seg_pharma_retail` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
