
DROP TABLE IF EXISTS `seg_opaccommodation_details`;
CREATE TABLE `seg_opaccommodation_details` (
  `refno` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `room_nr` mediumint(8) unsigned NOT NULL,
  `group_nr` smallint(5) unsigned NOT NULL,
  `charge` decimal(10,4) NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`,`entry_no`),
  CONSTRAINT `FK_seg_opaccommodation_details_hdr` FOREIGN KEY (`refno`) REFERENCES `seg_opaccommodation` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
;
DELIMITER ;
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
