
DROP TABLE IF EXISTS `seg_caserate_medical1`;
CREATE TABLE `seg_caserate_medical1` (
  `icd_code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `package` varchar(255) DEFAULT NULL,
  `hf` double DEFAULT NULL,
  `pf` double DEFAULT NULL,
  KEY `package` (`package`),
  KEY `icd_code` (`icd_code`)
);
