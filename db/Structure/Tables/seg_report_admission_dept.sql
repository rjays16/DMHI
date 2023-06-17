
DROP TABLE IF EXISTS `seg_report_admission_dept`;
CREATE TABLE `seg_report_admission_dept` (
  `nr` mediumint(8) NOT NULL,
  `dept_name` varchar(60) DEFAULT NULL,
  `dates` date NOT NULL DEFAULT '0000-00-00',
  `admission` int(11) DEFAULT NULL,
  PRIMARY KEY (`nr`,`dates`)
);
