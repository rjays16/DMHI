
DROP TABLE IF EXISTS `seg_lab_serv_discounts`;
CREATE TABLE `seg_lab_serv_discounts` (
  `refno` varchar(12) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  KEY `FK_seg_lab_serv_discounts` (`refno`),
  CONSTRAINT `FK_seg_lab_serv_discounts` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
