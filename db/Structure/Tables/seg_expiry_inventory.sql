
DROP TABLE IF EXISTS `seg_expiry_inventory`;
CREATE TABLE `seg_expiry_inventory` (
  `item_code` varchar(25) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `expiry_date` date NOT NULL,
  `qty` double DEFAULT NULL,
  PRIMARY KEY (`item_code`,`area_code`,`expiry_date`),
  CONSTRAINT `FK_Expiry_Inventory_care_pharma_products_main(VARCHAR)` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE
);
