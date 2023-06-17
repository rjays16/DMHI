
DROP TABLE IF EXISTS `seg_lab_result_choices`;
CREATE TABLE `seg_lab_result_choices` (
  `param_id` int(10) NOT NULL,
  `choice_nr` int(11) NOT NULL,
  `choice` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`param_id`,`choice_nr`)
);
