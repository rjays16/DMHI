
DROP TABLE IF EXISTS `seg_equipment_order_items`;
CREATE TABLE `seg_equipment_order_items` (
  `equipment_id` varchar(25) NOT NULL,
  `refno` varchar(10) NOT NULL,
  `number_of_usage` int(11) DEFAULT NULL,
  `original_price` double DEFAULT NULL,
  `discounted_price` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  PRIMARY KEY (`equipment_id`,`refno`),
  KEY `FK_seg_equipment_order_items` (`refno`),
  CONSTRAINT `FK_seg_equipment_order_items` FOREIGN KEY (`refno`) REFERENCES `seg_equipment_orders` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_equipment_order_items2` FOREIGN KEY (`equipment_id`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE
);
