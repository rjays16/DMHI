
DROP TABLE IF EXISTS `seg_hospital_service_price_details`;
CREATE TABLE `seg_hospital_service_price_details` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(15) NOT NULL,
  `price_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ref_source` enum('LB','RD','PH','MS','O') NOT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`ref_source`),
  CONSTRAINT `FK_seg_hospital_service_price_details` FOREIGN KEY (`refno`) REFERENCES `seg_hospital_service_price` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
