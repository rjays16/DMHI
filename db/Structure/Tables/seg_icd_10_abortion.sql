
DROP TABLE IF EXISTS `seg_icd_10_abortion`;
CREATE TABLE `seg_icd_10_abortion` (
  `id` varchar(15) NOT NULL,
  `diagnosis` varchar(300) NOT NULL,
  `icd_10_spontaneous` varchar(15) DEFAULT NULL,
  `icd_10_induced` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`,`diagnosis`),
  KEY `diagnosis` (`diagnosis`),
  KEY `code` (`icd_10_spontaneous`)
);
