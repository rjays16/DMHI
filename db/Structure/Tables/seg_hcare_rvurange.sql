
DROP TABLE IF EXISTS `seg_hcare_rvurange`;
CREATE TABLE `seg_hcare_rvurange` (
  `bsked_id` int(11) unsigned NOT NULL,
  `range_start` int(4) unsigned NOT NULL DEFAULT '0',
  `range_end` int(4) unsigned NOT NULL DEFAULT '0',
  `fixedamount` double(10,4) NOT NULL,
  `minamount` double(10,4) NOT NULL,
  `amountlimit` double(10,4) NOT NULL DEFAULT '0.0000',
  `rateperRVU` double(10,4) NOT NULL DEFAULT '0.0000',
  `percentofSF` double(6,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (`bsked_id`,`range_start`),
  CONSTRAINT `FK_seg_hcare_RVUrange` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
