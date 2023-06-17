
DROP TABLE IF EXISTS `seg_industrial_med_chart_details`;
CREATE TABLE `seg_industrial_med_chart_details` (
  `exam_nr` int(5) NOT NULL,
  `exam_type` int(5) NOT NULL,
  `exam_type_list` int(5) NOT NULL,
  `remarks` text,
  `dr_nr` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`exam_nr`,`exam_type`,`exam_type_list`)
);
