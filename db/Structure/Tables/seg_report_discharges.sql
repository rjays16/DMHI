
DROP TABLE IF EXISTS `seg_report_discharges`;
CREATE TABLE `seg_report_discharges` (
  `dates` date NOT NULL,
  `discharges` int(11) DEFAULT NULL,
  `discharges_alive` int(11) DEFAULT NULL,
  `discharges_died` int(11) DEFAULT NULL,
  `discharges_noresult` int(11) DEFAULT NULL,
  `total_no_days` int(11) DEFAULT NULL,
  PRIMARY KEY (`dates`)
);
