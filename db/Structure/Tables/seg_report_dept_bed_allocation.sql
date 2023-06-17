
DROP TABLE IF EXISTS `seg_report_dept_bed_allocation`;
CREATE TABLE `seg_report_dept_bed_allocation` (
  `id` varchar(10) NOT NULL,
  `dept_nr` varchar(10) NOT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `pay_allocated_bed` int(2) DEFAULT NULL,
  `service_allocated_bed` int(2) DEFAULT NULL,
  `ordering` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`id`,`dept_nr`)
);
