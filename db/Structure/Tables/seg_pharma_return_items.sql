
DROP TABLE IF EXISTS `seg_pharma_return_items`;
CREATE TABLE `seg_pharma_return_items` (
  `return_nr` varchar(10) NOT NULL,
  `ref_no` varchar(10) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  `quantity` int(10) DEFAULT NULL,
  PRIMARY KEY (`return_nr`,`ref_no`,`bestellnum`),
  KEY `FK_seg_pharma_order_items_pharma_products_main` (`bestellnum`),
  KEY `FK_seg_pharma_return_items_orders` (`ref_no`),
  KEY `FK_bestellnumandrefnojointindex` (`ref_no`,`bestellnum`),
  CONSTRAINT `FK_seg_pharma_return_items` FOREIGN KEY (`return_nr`) REFERENCES `seg_pharma_returns` (`return_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_return_items_orders` FOREIGN KEY (`ref_no`) REFERENCES `seg_pharma_orders` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_return_items_products` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
