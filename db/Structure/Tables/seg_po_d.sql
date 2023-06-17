
DROP TABLE IF EXISTS `seg_po_d`;
CREATE TABLE `seg_po_d` (
  `po_no` varchar(12) DEFAULT NULL,
  `refno` varchar(20) NOT NULL,
  `item_code` varchar(25) DEFAULT NULL,
  `unit_id` int(10) unsigned DEFAULT NULL,
  `po_qty` double DEFAULT NULL,
  `is_unitperpc` tinyint(1) DEFAULT NULL,
  `id` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_code` (`item_code`),
  KEY `po_no` (`po_no`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `FK_seg_po_d_care_pharma_products_main` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_po_d_seg_po_h` FOREIGN KEY (`po_no`) REFERENCES `seg_po_h` (`po_no`) ON UPDATE NO ACTION,
  CONSTRAINT `FK_seg_po_d_seg_unit` FOREIGN KEY (`unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE
);
