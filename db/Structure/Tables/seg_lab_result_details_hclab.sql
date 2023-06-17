
DROP TABLE IF EXISTS `seg_lab_result_details_hclab`;
CREATE TABLE `seg_lab_result_details_hclab` (
  `refno` varchar(12) DEFAULT NULL,
  `line_no` mediumint(20) DEFAULT NULL,
  `test_code` varchar(50) DEFAULT NULL,
  `test_name` varchar(50) DEFAULT NULL,
  `data_type` varchar(50) DEFAULT NULL,
  `result_value` text,
  `unit` varchar(50) DEFAULT NULL,
  `result_flag` varchar(10) DEFAULT NULL,
  `range` varchar(50) DEFAULT NULL,
  `result_status` varchar(50) DEFAULT NULL,
  `test_comment` text,
  `mlt_code` varchar(50) DEFAULT NULL,
  `mlt_name` varchar(100) DEFAULT NULL,
  `reported_dt` datetime DEFAULT NULL,
  `performed_lab_code` varbinary(20) DEFAULT NULL,
  `performed_lab_name` varchar(100) DEFAULT NULL,
  `parent_item` varchar(50) DEFAULT NULL
);
