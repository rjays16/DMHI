
DROP TABLE IF EXISTS `seg_lab_result_row`;
CREATE TABLE `seg_lab_result_row` (
  `table_nr` int(11) NOT NULL,
  `row_nr` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`table_nr`,`row_nr`)
);
