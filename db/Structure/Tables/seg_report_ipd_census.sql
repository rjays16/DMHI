
DROP TABLE IF EXISTS `seg_report_ipd_census`;
CREATE TABLE `seg_report_ipd_census` (
  `dates` date NOT NULL,
  `initial_census` int(11) DEFAULT NULL,
  PRIMARY KEY (`dates`)
);
