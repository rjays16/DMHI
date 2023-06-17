
DROP TABLE IF EXISTS `seg_report_prev_census_dept`;
CREATE TABLE `seg_report_prev_census_dept` (
  `nr` mediumint(8) NOT NULL,
  `dept_name` varchar(60) DEFAULT NULL,
  `admitted` int(11) DEFAULT NULL,
  `discharges` int(11) DEFAULT NULL,
  PRIMARY KEY (`nr`)
);
