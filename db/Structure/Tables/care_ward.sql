
DROP TABLE IF EXISTS `care_ward`;
CREATE TABLE `care_ward` (
  `nr` smallint(5) unsigned NOT NULL,
  `accomodation_type` smallint(6) NOT NULL,
  `ward_id` varchar(35) NOT NULL COMMENT 'room type -09222007',
  `name` varchar(35) NOT NULL,
  `is_temp_closed` tinyint(1) NOT NULL DEFAULT '0',
  `is_orpacu` tinyint(1) NOT NULL DEFAULT '0',
  `date_create` date NOT NULL DEFAULT '0000-00-00',
  `date_close` date NOT NULL DEFAULT '0000-00-00',
  `description` text,
  `info` tinytext,
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `room_nr_start` smallint(6) NOT NULL DEFAULT '0',
  `room_nr_end` smallint(6) NOT NULL DEFAULT '0',
  `roomprefix` varchar(4) DEFAULT NULL,
  `mandatory_excess` double(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(25) NOT NULL,
  `prototype` enum('payward','service','mhc','or','mdc','other') NOT NULL DEFAULT 'payward' COMMENT '''payward'',''service'',''mhc'',''or'',''mdc'',''other''',
  `history` text NOT NULL,
  `modify_id` varchar(25) NOT NULL DEFAULT '0',
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(25) NOT NULL DEFAULT '0',
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `ward_id` (`ward_id`),
  KEY `FK_care_ward_accommodation_type` (`accomodation_type`),
  KEY `status_index` (`status`),
  KEY `datecreate_index` (`date_create`),
  CONSTRAINT `FK_care_ward_accommodation_type` FOREIGN KEY (`accomodation_type`) REFERENCES `seg_accomodation_type` (`accomodation_nr`) ON UPDATE CASCADE
);
;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
