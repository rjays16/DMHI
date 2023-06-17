
DROP TABLE IF EXISTS `seg_industrial_comp_price`;
CREATE TABLE `seg_industrial_comp_price` (
  `company_id` varchar(12) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `price` double(10,4) DEFAULT NULL,
  `service_area` enum('RD','LB','MD','PH','OT') NOT NULL,
  PRIMARY KEY (`company_id`,`service_code`,`service_area`),
  KEY `FK_seg_industrial_comp_price` (`service_code`),
  CONSTRAINT `FK_seg_industrial_comp_price` FOREIGN KEY (`company_id`) REFERENCES `seg_industrial_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
