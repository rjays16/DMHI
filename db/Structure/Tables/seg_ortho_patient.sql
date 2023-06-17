
DROP TABLE IF EXISTS `seg_ortho_patient`;
CREATE TABLE `seg_ortho_patient` (
  `hrn` varchar(12) NOT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`hrn`)
);
