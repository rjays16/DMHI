
DROP TABLE IF EXISTS `seg_issuance_details`;
CREATE TABLE `seg_issuance_details` (
  `refno` varchar(12) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `serial_no` varchar(25) NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `item_qty` double DEFAULT '0',
  `unit_id` int(10) unsigned DEFAULT NULL,
  `avg_cost` double DEFAULT '0',
  `is_unitperpc` tinyint(1) DEFAULT NULL,
  `is_acknowledged` tinyint(1) NOT NULL,
  `status` varchar(20) DEFAULT 'ISSUED',
  PRIMARY KEY (`refno`,`item_code`,`serial_no`,`expiry_date`),
  KEY `FK_seg_issuance_details_units` (`unit_id`),
  KEY `FK_seg_issuance_details_product` (`item_code`),
  CONSTRAINT `FK_seg_issuance_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_issuance` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_issuance_details_product` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_issuance_details_seg_internal_request(VARCHAR)` FOREIGN KEY (`refno`) REFERENCES `seg_issuance` (`refno`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_issuance_details_units` FOREIGN KEY (`unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE
);
