
DROP TABLE IF EXISTS `seg_lab_result_column`;
CREATE TABLE `seg_lab_result_column` (
  `table_nr` int(11) NOT NULL,
  `col_nr` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`table_nr`,`col_nr`)
);
