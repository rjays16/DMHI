
DROP TABLE IF EXISTS `seg_social_case_management`;
CREATE TABLE `seg_social_case_management` (
  `pid` int(11) NOT NULL,
  `encounter_nr` varchar(20) NOT NULL,
  `planning` varchar(50) DEFAULT NULL,
  `provision` varchar(50) DEFAULT NULL,
  `outgoing` varchar(50) DEFAULT NULL,
  `incoming` varchar(50) DEFAULT NULL,
  `leading_reasons` varchar(50) DEFAULT NULL,
  `social_work` varchar(50) DEFAULT NULL,
  `discharge_services` varchar(50) DEFAULT NULL,
  `case_con` varchar(50) DEFAULT NULL,
  `follow_up` varchar(50) DEFAULT NULL,
  `coordination` varchar(50) DEFAULT NULL,
  `others_coordination` varchar(100) DEFAULT NULL,
  `documentation` varchar(50) DEFAULT NULL,
  `others_documentation` varchar(100) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pid`,`encounter_nr`)
);
