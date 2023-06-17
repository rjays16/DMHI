
DROP TABLE IF EXISTS `seg_report_discharges_dept`;
CREATE TABLE `seg_report_discharges_dept` (
  `nr` mediumint(8) NOT NULL,
  `dept_name` varchar(60) DEFAULT NULL,
  `dates` date NOT NULL DEFAULT '0000-00-00',
  `discharges` int(11) DEFAULT NULL,
  `discharges_alive` int(11) DEFAULT NULL,
  `discharges_died` int(11) DEFAULT NULL,
  `discharges_noresult` int(11) DEFAULT NULL,
  `total_no_days` int(11) DEFAULT NULL,
  PRIMARY KEY (`nr`,`dates`)
);
