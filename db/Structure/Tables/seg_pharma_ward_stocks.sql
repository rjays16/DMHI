
DROP TABLE IF EXISTS `seg_pharma_ward_stocks`;
CREATE TABLE `seg_pharma_ward_stocks` (
  `stock_nr` int(11) NOT NULL,
  `pharma_area` varchar(10) NOT NULL DEFAULT 'IP',
  `stock_date` datetime NOT NULL,
  `ward_id` int(11) NOT NULL,
  `create_id` varchar(40) NOT NULL,
  `create_time` datetime NOT NULL,
  `modify_id` varchar(40) NOT NULL,
  `modify_time` datetime NOT NULL,
  `history` text,
  PRIMARY KEY (`stock_nr`),
  KEY `FK_seg_pharma_ward_stocks_wards` (`ward_id`),
  KEY `FK_seg_pharma_ward_stocks` (`pharma_area`),
  CONSTRAINT `FK_seg_pharma_ward_stocks` FOREIGN KEY (`pharma_area`) REFERENCES `seg_pharma_areas` (`area_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_ward_stocks_wards` FOREIGN KEY (`ward_id`) REFERENCES `seg_pharma_wards` (`ward_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
