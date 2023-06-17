
DROP TABLE IF EXISTS `seg_charity_amount`;
CREATE TABLE `seg_charity_amount` (
  `ref_no` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD') NOT NULL,
  `grant_dte` datetime NOT NULL,
  `sw_nr` int(11) NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ref_no`,`ref_source`),
  KEY `FK_seg_charity_amount_personell` (`sw_nr`),
  CONSTRAINT `FK_seg_charity_amount_personell` FOREIGN KEY (`sw_nr`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
