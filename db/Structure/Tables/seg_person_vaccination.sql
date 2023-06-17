
DROP TABLE IF EXISTS `seg_person_vaccination`;
CREATE TABLE `seg_person_vaccination` (
  `pid` varchar(12) NOT NULL,
  `vac_details` varchar(60) DEFAULT NULL,
  `vac_date` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pid`)
);
