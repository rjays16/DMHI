
DROP TABLE IF EXISTS `seg_lab_results_details`;
CREATE TABLE `seg_lab_results_details` (
  `refno` varchar(12) NOT NULL,
  `line_no` mediumint(20) NOT NULL,
  `test_code` varchar(50) DEFAULT NULL,
  `test_name` varchar(50) DEFAULT NULL,
  `data_type` varchar(50) DEFAULT NULL,
  `result_value` text,
  `unit` varchar(50) DEFAULT NULL,
  `result_flag` varchar(10) DEFAULT NULL,
  `ranges` varchar(50) DEFAULT NULL,
  `result_status` varchar(50) DEFAULT NULL,
  `test_comment` text,
  `mlt_code` varchar(50) DEFAULT NULL,
  `mlt_name` varchar(100) DEFAULT NULL,
  `reported_dt` datetime DEFAULT NULL,
  `performed_lab_code` varbinary(20) DEFAULT NULL,
  `performed_lab_name` varchar(100) DEFAULT NULL,
  `parent_item` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`refno`,`line_no`),
  CONSTRAINT `FK_seg_lab_results_details` FOREIGN KEY (`refno`) REFERENCES `seg_lab_results` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
