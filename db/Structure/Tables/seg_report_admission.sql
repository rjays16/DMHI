
DROP TABLE IF EXISTS `seg_report_admission`;
CREATE TABLE `seg_report_admission` (
  `dates` date NOT NULL,
  `admission` int(11) DEFAULT NULL,
  PRIMARY KEY (`dates`)
);
