
DROP TABLE IF EXISTS `seg_med_rdiscount`;
CREATE TABLE `seg_med_rdiscount` (
  `refno` varchar(20) NOT NULL,
  `discountid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`refno`,`discountid`),
  KEY `FK_seg_med_rdiscount` (`discountid`),
  CONSTRAINT `seg_med_rdiscount_ibfk_1` FOREIGN KEY (`refno`) REFERENCES `seg_med_retail` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
