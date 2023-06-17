
DROP TABLE IF EXISTS `seg_icd_10_deliveries`;
CREATE TABLE `seg_icd_10_deliveries` (
  `id` varchar(15) NOT NULL,
  `diagnosis` varchar(300) DEFAULT NULL,
  `icd_10` varchar(15) NOT NULL,
  PRIMARY KEY (`id`,`icd_10`),
  KEY `diagnosis` (`diagnosis`),
  KEY `code` (`icd_10`)
);
