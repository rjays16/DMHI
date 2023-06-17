
DROP TABLE IF EXISTS `seg_pharma_order_items`;
CREATE TABLE `seg_pharma_order_items` (
  `refno` varchar(10) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  `quantity` smallint(5) NOT NULL DEFAULT '1',
  `request_flag` varchar(10) DEFAULT NULL,
  `discount_class` varchar(10) DEFAULT NULL,
  `pricecash` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `pricecharge` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `price_orig` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `is_consigned` tinyint(1) NOT NULL DEFAULT '0',
  `serve_status` enum('S','P','N') NOT NULL DEFAULT 'N',
  `serve_remarks` tinytext NOT NULL,
  `serve_id` varchar(35) DEFAULT NULL,
  `serve_dt` datetime DEFAULT NULL,
  `cancel_reason` text,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `returns` smallint(5) NOT NULL DEFAULT '0',
  `is_unused` tinyint(1) NOT NULL DEFAULT '0',
  `unused_qty` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`refno`,`bestellnum`),
  KEY `FK_seg_pharma_order_items_pharma_products_main` (`bestellnum`),
  KEY `FK_seg_pharma_order_items_request_flag` (`request_flag`),
  KEY `FK_seg_pharma_order_items_discount` (`discount_class`),
  KEY `FK_servestatusindex` (`serve_status`),
  KEY `FK_statusandrflagandrefnoandbestellnum` (`refno`,`bestellnum`,`request_flag`,`serve_status`),
  CONSTRAINT `FK_seg_pharma_order_items` FOREIGN KEY (`refno`) REFERENCES `seg_pharma_orders` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_order_items_discount` FOREIGN KEY (`discount_class`) REFERENCES `seg_discount` (`discountid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_order_items_pharma_products_main` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_order_items_request_flag` FOREIGN KEY (`request_flag`) REFERENCES `seg_type_charge` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
