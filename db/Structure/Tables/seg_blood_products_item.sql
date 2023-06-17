
DROP TABLE IF EXISTS `seg_blood_products_item`;
CREATE TABLE `seg_blood_products_item` (
  `service_code` varchar(10) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  PRIMARY KEY (`service_code`,`item_code`),
  KEY `FK_seg_blood_products_item` (`item_code`),
  CONSTRAINT `FK_seg_blood_products_component` FOREIGN KEY (`service_code`) REFERENCES `seg_blood_component` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_blood_products_item` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE NO ACTION ON UPDATE CASCADE
);
