
DROP TABLE IF EXISTS `seg_med_rdetails`;
CREATE TABLE `seg_med_rdetails` (
  `refno` varchar(20) NOT NULL,
  `bestellnum` varchar(25) NOT NULL,
  `entrynum` smallint(5) unsigned NOT NULL,
  `qty` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `rpriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`refno`,`bestellnum`,`entrynum`),
  KEY `FK_seg_med_rdetails` (`bestellnum`),
  CONSTRAINT `seg_med_rdetails_ibfk_1` FOREIGN KEY (`refno`) REFERENCES `seg_med_retail` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seg_med_rdetails_ibfk_2` FOREIGN KEY (`bestellnum`) REFERENCES `care_med_products_main` (`bestellnum`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
