
DROP TABLE IF EXISTS `seg_lab_serv_serial`;
CREATE TABLE `seg_lab_serv_serial` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `lis_order_no` varchar(20) NOT NULL,
  `nth_take` smallint(2) NOT NULL,
  `is_served` tinyint(1) DEFAULT '0',
  `with_result` tinyint(1) DEFAULT '0',
  `is_repeated` tinyint(1) DEFAULT '0',
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`refno`,`service_code`,`nth_take`)
);
