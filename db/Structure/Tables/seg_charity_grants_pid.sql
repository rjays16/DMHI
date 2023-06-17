
DROP TABLE IF EXISTS `seg_charity_grants_pid`;
CREATE TABLE `seg_charity_grants_pid` (
  `pid` varchar(12) NOT NULL,
  `grant_dte` datetime NOT NULL,
  `sw_nr` int(11) NOT NULL,
  `discountid` varchar(10) NOT NULL,
  `discount` decimal(10,8) NOT NULL,
  `discount_amnt` decimal(10,2) DEFAULT NULL,
  `notes` text,
  `personal_circumstance` text,
  `community_situation` text,
  `nature_of_disease` text,
  `reason` varchar(10) DEFAULT NULL,
  `other_name` varchar(50) DEFAULT NULL,
  `id_number` varchar(20) DEFAULT NULL,
  `status` enum('valid','expired','cancelled','deleted') DEFAULT 'valid',
  PRIMARY KEY (`pid`,`grant_dte`),
  KEY `FK_seg_charity_grants_personel` (`sw_nr`),
  KEY `discountid` (`discountid`),
  CONSTRAINT `FK_seg_charity_grants_pid` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
