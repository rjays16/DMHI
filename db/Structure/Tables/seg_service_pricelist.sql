
DROP TABLE IF EXISTS `seg_service_pricelist`;
CREATE TABLE `seg_service_pricelist` (
  `area_code` varchar(10) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `price_cash` decimal(10,2) DEFAULT '0.00',
  `price_charge` decimal(10,2) DEFAULT '0.00',
  `ref_source` enum('LB','RD','PH','MS','O') NOT NULL,
  `history` text,
  `create_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`area_code`,`service_code`,`ref_source`),
  KEY `FK_seg_service_discounts` (`service_code`),
  CONSTRAINT `FK_seg_service_pricelist` FOREIGN KEY (`area_code`) REFERENCES `seg_service_area` (`area_code`) ON DELETE CASCADE ON UPDATE CASCADE
);
