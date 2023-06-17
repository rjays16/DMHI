
DROP TABLE IF EXISTS `seg_service_discounts`;
CREATE TABLE `seg_service_discounts` (
  `discountid` varchar(10) NOT NULL,
  `service_code` varchar(25) NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `service_area` enum('LB','RD','MD','PH') NOT NULL,
  PRIMARY KEY (`discountid`,`service_code`,`service_area`),
  KEY `FK_seg_service_discounts` (`service_code`)
);
