
DROP TABLE IF EXISTS `seg_hosp_eod`;
CREATE TABLE `seg_hosp_eod` (
  `bestellnum` varchar(25) NOT NULL,
  `eoddate` date NOT NULL DEFAULT '0000-00-00',
  `eodqty` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `area_nr` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`bestellnum`),
  KEY `FK_seg_hosp_eod` (`area_nr`),
  CONSTRAINT `seg_hosp_eod_ibfk_1` FOREIGN KEY (`area_nr`) REFERENCES `care_department` (`nr`) ON UPDATE CASCADE
);
