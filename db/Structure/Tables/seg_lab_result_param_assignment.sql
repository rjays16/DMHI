
DROP TABLE IF EXISTS `seg_lab_result_param_assignment`;
CREATE TABLE `seg_lab_result_param_assignment` (
  `param_id` smallint(5) NOT NULL,
  `service_code` varchar(20) NOT NULL,
  `order_nr` smallint(5) NOT NULL,
  `is_copied` tinyint(1) DEFAULT '0',
  `status` varchar(30) DEFAULT '',
  `create_id` varchar(30) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(30) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`param_id`,`service_code`)
);
