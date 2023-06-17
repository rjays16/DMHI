
DROP TABLE IF EXISTS `seg_pharma_orders`;
CREATE TABLE `seg_pharma_orders` (
  `refno` varchar(12) NOT NULL,
  `department` enum('P','E','O') NOT NULL DEFAULT 'P',
  `pharma_area` varchar(10) NOT NULL DEFAULT 'IP',
  `request_source` varchar(10) DEFAULT NULL,
  `orderdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pid` varchar(12) DEFAULT NULL,
  `walkin_pid` varchar(12) DEFAULT NULL,
  `request_dept` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `related_refno` varchar(12) DEFAULT NULL,
  `related_refsource` enum('ER','LD','OR','RD') DEFAULT NULL,
  `ordername` varchar(200) NOT NULL,
  `orderaddress` varchar(250) NOT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `discount` decimal(10,8) DEFAULT NULL,
  `charge_type` enum('PERSONAL','PHIC','LINGAP','CMAP','MISSION','PCSO','CAO','COH') NOT NULL DEFAULT 'PERSONAL',
  `is_cash` tinyint(1) NOT NULL DEFAULT '0',
  `is_tpl` tinyint(1) NOT NULL DEFAULT '0',
  `is_urgent` tinyint(1) NOT NULL DEFAULT '0',
  `amount_due` decimal(10,4) DEFAULT NULL,
  `serve_status` enum('S','P','N') NOT NULL DEFAULT 'N',
  `comments` varchar(200) DEFAULT NULL,
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_pharma_orders_person` (`pid`),
  KEY `FK_seg_pharma_orders` (`pharma_area`),
  KEY `FK_seg_pharma_orders_walkin` (`walkin_pid`),
  KEY `FK_seg_pharma_orders_requestsrc` (`request_source`),
  KEY `encounternr_index` (`encounter_nr`),
  KEY `orderdate_index` (`orderdate`),
  KEY `ordername_index` (`ordername`),
  CONSTRAINT `FK_seg_pharma_orders` FOREIGN KEY (`pharma_area`) REFERENCES `seg_pharma_areas` (`area_code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_orders_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_orders_requestsrc` FOREIGN KEY (`request_source`) REFERENCES `seg_type_request_source` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_pharma_orders_walkin` FOREIGN KEY (`walkin_pid`) REFERENCES `seg_walkin` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
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
