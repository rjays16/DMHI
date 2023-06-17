
DROP TABLE IF EXISTS `seg_more_phorder_details`;
CREATE TABLE `seg_more_phorder_details` (
  `refno` varchar(12) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `quantity` double NOT NULL,
  `unit_id` int(10) NOT NULL,
  `is_unitperpc` tinyint(1) NOT NULL,
  `unit_price` decimal(10,4) NOT NULL,
  PRIMARY KEY (`refno`,`bestellnum`,`entry_no`),
  KEY `FK_seg_more_phorder_details_products` (`bestellnum`),
  CONSTRAINT `FK_seg_more_phorder_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_more_phorder` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_more_phorder_details_products` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
