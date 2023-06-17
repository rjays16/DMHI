
DROP TABLE IF EXISTS `seg_hosp_inventory`;
CREATE TABLE `seg_hosp_inventory` (
  `bestellnum` varchar(25) NOT NULL,
  `qty` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `avgcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `area_nr` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`bestellnum`),
  KEY `FK_seg_hosp_inventory` (`area_nr`),
  CONSTRAINT `seg_hosp_inventory_ibfk_1` FOREIGN KEY (`area_nr`) REFERENCES `care_department` (`nr`) ON UPDATE CASCADE
);
