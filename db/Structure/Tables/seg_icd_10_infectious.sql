
DROP TABLE IF EXISTS `seg_icd_10_infectious`;
CREATE TABLE `seg_icd_10_infectious` (
  `id` varchar(15) NOT NULL,
  `diagnosis` varchar(300) DEFAULT NULL,
  `other_name` varchar(300) DEFAULT NULL,
  `icd_10` varchar(15) NOT NULL,
  PRIMARY KEY (`id`,`icd_10`),
  KEY `diagnosis` (`diagnosis`),
  KEY `code` (`icd_10`)
);
