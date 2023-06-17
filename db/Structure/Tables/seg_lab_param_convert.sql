
DROP TABLE IF EXISTS `seg_lab_param_convert`;
CREATE TABLE `seg_lab_param_convert` (
  `param_id` smallint(5) unsigned NOT NULL,
  `si_to_cu_factor` varchar(10) DEFAULT NULL,
  `cu_to_si_factor` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`param_id`)
);
