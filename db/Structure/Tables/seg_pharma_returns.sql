
DROP TABLE IF EXISTS `seg_pharma_returns`;
CREATE TABLE `seg_pharma_returns` (
  `return_nr` varchar(10) NOT NULL,
  `return_date` datetime DEFAULT '0000-00-00 00:00:00',
  `pid` varchar(25) DEFAULT NULL,
  `encounter_nr` varchar(10) DEFAULT NULL,
  `return_name` varchar(255) DEFAULT NULL,
  `return_address` tinytext,
  `refund_amount` decimal(10,4) DEFAULT NULL,
  `refund_amount_fixed` decimal(10,4) DEFAULT NULL,
  `pharma_area` varchar(10) DEFAULT NULL,
  `comments` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `history` text,
  PRIMARY KEY (`return_nr`),
  KEY `FK_seg_pharma_returns` (`pharma_area`),
  KEY `FK_IndexOnEncounterNr` (`encounter_nr`),
  CONSTRAINT `FK_seg_pharma_returns_care_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
