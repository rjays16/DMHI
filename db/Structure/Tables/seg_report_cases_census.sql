
DROP TABLE IF EXISTS `seg_report_cases_census`;
CREATE TABLE `seg_report_cases_census` (
  `pid` varchar(12) NOT NULL,
  `no_encounters` int(11) DEFAULT NULL,
  PRIMARY KEY (`pid`)
);
