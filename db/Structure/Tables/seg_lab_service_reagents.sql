
DROP TABLE IF EXISTS `seg_lab_service_reagents`;
CREATE TABLE `seg_lab_service_reagents` (
  `service_code` varchar(12) NOT NULL,
  `reagent_code` varchar(12) NOT NULL,
  `item_qty` double DEFAULT NULL,
  `unit_id` int(10) DEFAULT NULL,
  `is_unitperpc` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`service_code`,`reagent_code`),
  KEY `FK_seg_lab_service_reagents` (`reagent_code`),
  CONSTRAINT `FK_seg_lab_service_reagents` FOREIGN KEY (`reagent_code`) REFERENCES `seg_lab_reagents` (`reagent_code`) ON UPDATE CASCADE
);
