
DROP TABLE IF EXISTS `seg_pharma_products_availability`;
CREATE TABLE `seg_pharma_products_availability` (
  `bestellnum` varchar(25) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  PRIMARY KEY (`bestellnum`,`area_code`),
  KEY `FK_seg_pharma_products_availability` (`area_code`),
  CONSTRAINT `FK_seg_pharma_products_availability2` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE
);
