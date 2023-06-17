
DROP TABLE IF EXISTS `seg_lab_result_groupname`;
CREATE TABLE `seg_lab_result_groupname` (
  `group_id` smallint(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT '',
  `create_id` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(30) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`)
);
