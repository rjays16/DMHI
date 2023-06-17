
DROP TABLE IF EXISTS `seg_lab_result_paramgroups`;
CREATE TABLE `seg_lab_result_paramgroups` (
  `param_group_id` smallint(5) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` varchar(30) DEFAULT '',
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`param_group_id`)
);
