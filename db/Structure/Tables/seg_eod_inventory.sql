
DROP TABLE IF EXISTS `seg_eod_inventory`;
CREATE TABLE `seg_eod_inventory` (
  `item_code` varchar(25) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `eod_date` date NOT NULL,
  `expiry_date` date NOT NULL DEFAULT '0000-00-00',
  `serial_no` varchar(25) NOT NULL,
  `eod_qty` double DEFAULT NULL,
  PRIMARY KEY (`item_code`,`area_code`,`eod_date`,`expiry_date`,`serial_no`),
  CONSTRAINT `FK_eod_inventory_care_pharma_products_main` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE
);
