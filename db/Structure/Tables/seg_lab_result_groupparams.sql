
DROP TABLE IF EXISTS `seg_lab_result_groupparams`;
CREATE TABLE `seg_lab_result_groupparams` (
  `group_id` smallint(5) NOT NULL,
  `service_code` varchar(20) NOT NULL,
  `order_nr` smallint(5) DEFAULT NULL,
  `status` varchar(30) DEFAULT '',
  `create_id` varchar(30) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(30) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`,`service_code`),
  CONSTRAINT `FK_seg_lab_result_groupparams` FOREIGN KEY (`group_id`) REFERENCES `seg_lab_result_groupname` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
