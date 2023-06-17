
DROP TABLE IF EXISTS `seg_internal_request_details`;
CREATE TABLE `seg_internal_request_details` (
  `refno` varchar(12) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `item_qty` double DEFAULT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `is_unitperpc` tinyint(1) NOT NULL,
  PRIMARY KEY (`refno`,`item_code`,`unit_id`),
  KEY `FK_seg_internal_request_details_unit` (`unit_id`),
  KEY `FK_seg_internal_request_details_product` (`item_code`),
  CONSTRAINT `FK_seg_internal_request_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_internal_request` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_internal_request_details_product` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_internal_request_details_unit` FOREIGN KEY (`unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE
);
