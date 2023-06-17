
DROP TABLE IF EXISTS `seg_delivery_details`;
CREATE TABLE `seg_delivery_details` (
  `refno` varchar(12) NOT NULL,
  `po_no` varchar(12) DEFAULT NULL,
  `item_code` varchar(25) NOT NULL,
  `unit_price` double(10,4) NOT NULL,
  `item_qty` double NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `is_unitperpc` tinyint(1) NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `serial_no` varchar(25) NOT NULL,
  `prev_qty` double NOT NULL,
  `prev_avg_cost` double(10,4) NOT NULL,
  `id` char(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_code` (`item_code`),
  KEY `po_no` (`po_no`),
  KEY `refno` (`refno`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `seg_delivery_details_ibfk_1` FOREIGN KEY (`refno`) REFERENCES `seg_delivery` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seg_delivery_details_ibfk_2` FOREIGN KEY (`po_no`) REFERENCES `seg_po_h` (`po_no`) ON UPDATE CASCADE,
  CONSTRAINT `seg_delivery_details_ibfk_3` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `seg_delivery_details_ibfk_4` FOREIGN KEY (`unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE
);
