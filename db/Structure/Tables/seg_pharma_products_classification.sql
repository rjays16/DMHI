
DROP TABLE IF EXISTS `seg_pharma_products_classification`;
CREATE TABLE `seg_pharma_products_classification` (
  `class_code` int(11) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  PRIMARY KEY (`class_code`,`bestellnum`),
  KEY `FK_seg_pharma_products_classfication` (`bestellnum`),
  CONSTRAINT `FK_seg_pharma_products_classfication` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_products_classfication2` FOREIGN KEY (`class_code`) REFERENCES `seg_product_classification` (`class_code`) ON DELETE CASCADE ON UPDATE CASCADE
);
