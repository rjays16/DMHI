
DROP TABLE IF EXISTS `seg_caserate_medical`;
CREATE TABLE `seg_caserate_medical` (
  `caserate_id` int(11) NOT NULL,
  `icd_code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `group` text NOT NULL,
  `package` double(20,3) NOT NULL,
  `hf` double(20,3) NOT NULL,
  `pf` double(20,3) NOT NULL,
  PRIMARY KEY (`caserate_id`),
  KEY `icd_code` (`icd_code`)
);
