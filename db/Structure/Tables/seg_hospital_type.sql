
DROP TABLE IF EXISTS `seg_hospital_type`;
CREATE TABLE `seg_hospital_type` (
  `hosp_type` varchar(10) NOT NULL,
  `hosp_desc` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`hosp_type`)
);
