
DROP TABLE IF EXISTS `seg_lab_result_tables`;
CREATE TABLE `seg_lab_result_tables` (
  `param_id` int(10) NOT NULL,
  `table_nr` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`param_id`,`table_nr`)
);
