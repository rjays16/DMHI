
DROP TABLE IF EXISTS `seg_industrial_med_chart_list`;
CREATE TABLE `seg_industrial_med_chart_list` (
  `list_id` int(5) unsigned NOT NULL,
  `list_name` text,
  `list_datatype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`list_id`)
);
