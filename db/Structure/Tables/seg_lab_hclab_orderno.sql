
DROP TABLE IF EXISTS `seg_lab_hclab_orderno`;
CREATE TABLE `seg_lab_hclab_orderno` (
  `lis_order_no` varchar(20) NOT NULL,
  `refno` varchar(12) NOT NULL,
  PRIMARY KEY (`lis_order_no`),
  KEY `refno_index` (`refno`)
);
