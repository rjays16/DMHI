
DROP TABLE IF EXISTS `seg_report_charity_discount`;
CREATE TABLE `seg_report_charity_discount` (
  `pid` varchar(12) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  PRIMARY KEY (`pid`,`discountid`)
);
