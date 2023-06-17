
DROP TABLE IF EXISTS `seg_item_extended`;
CREATE TABLE `seg_item_extended` (
  `item_code` varchar(25) NOT NULL,
  `avg_cost` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `min_qty` double NOT NULL DEFAULT '0',
  `pack_unit_id` int(10) unsigned NOT NULL,
  `pc_unit_id` int(10) unsigned NOT NULL,
  `qty_per_pack` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_code`),
  KEY `FK_seg_item_extended_pack` (`pack_unit_id`),
  KEY `FK_seg_item_extended_pcs` (`pc_unit_id`),
  CONSTRAINT `FK_seg_item_extended_care_pharma_products_main(VARCHAR)` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_item_extended_pack` FOREIGN KEY (`pack_unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_item_extended_pcs` FOREIGN KEY (`pc_unit_id`) REFERENCES `seg_unit` (`unit_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_item_extended_product` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE
);
