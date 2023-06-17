
DROP TABLE IF EXISTS `seg_service_usage_details`;
CREATE TABLE `seg_service_usage_details` (
  `refno` varchar(12) NOT NULL,
  `ref_source` enum('LD','RD','BB') DEFAULT NULL,
  `service_code` varchar(10) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `qty_used` double NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`refno`,`service_code`,`item_code`),
  KEY `FK_seg_service_usage_details` (`refno`,`ref_source`),
  CONSTRAINT `FK_seg_service_usage_details` FOREIGN KEY (`refno`, `ref_source`) REFERENCES `seg_service_usage` (`refno`, `ref_source`) ON DELETE CASCADE ON UPDATE CASCADE
);
