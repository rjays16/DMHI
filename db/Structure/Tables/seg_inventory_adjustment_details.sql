
DROP TABLE IF EXISTS `seg_inventory_adjustment_details`;
CREATE TABLE `seg_inventory_adjustment_details` (
  `refno` varchar(12) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `is_unitperpc` tinyint(1) NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `serial_no` varchar(25) NOT NULL,
  `orig_qty` double NOT NULL,
  `adj_qty` double NOT NULL,
  `reason` varchar(10) NOT NULL,
  PRIMARY KEY (`refno`,`item_code`,`unit_id`,`expiry_date`,`serial_no`),
  KEY `FK_seg_inventory_adjustment_details_main` (`item_code`),
  KEY `FK_seg_inventory_adjustment_details_unit` (`unit_id`),
  CONSTRAINT `FK_seg_inventory_adjustment_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_inventory_adjustment` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_inventory_adjustment_details_main` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_inventory_adjustment_details_unit` FOREIGN KEY (`unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE
);
