
DROP TABLE IF EXISTS `seg_inventory`;
CREATE TABLE `seg_inventory` (
  `item_code` varchar(25) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `serial_no` varchar(25) NOT NULL,
  `qty` double DEFAULT NULL,
  PRIMARY KEY (`item_code`,`area_code`,`expiry_date`,`serial_no`),
  KEY `FK_seg_inventory` (`area_code`),
  CONSTRAINT `FK_inventory_care_pharma_products_main(VARCHAR)` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_inventory` FOREIGN KEY (`area_code`) REFERENCES `seg_areas` (`area_code`) ON UPDATE CASCADE
);
