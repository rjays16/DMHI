
DROP TABLE IF EXISTS `seg_pharma_ward_stock_items`;
CREATE TABLE `seg_pharma_ward_stock_items` (
  `stock_nr` int(11) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_nr`,`bestellnum`),
  CONSTRAINT `FK_seg_pharma_ward_stock_items` FOREIGN KEY (`stock_nr`) REFERENCES `seg_pharma_ward_stocks` (`stock_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
