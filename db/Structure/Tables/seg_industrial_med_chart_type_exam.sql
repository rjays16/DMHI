
DROP TABLE IF EXISTS `seg_industrial_med_chart_type_exam`;
CREATE TABLE `seg_industrial_med_chart_type_exam` (
  `id` int(5) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `with_dr_sig` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
