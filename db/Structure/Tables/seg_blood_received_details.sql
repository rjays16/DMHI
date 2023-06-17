
DROP TABLE IF EXISTS `seg_blood_received_details`;
CREATE TABLE `seg_blood_received_details` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `ordering` int(3) NOT NULL,
  `received_date` datetime DEFAULT NULL,
  `component` varchar(20) DEFAULT NULL,
  `result` varchar(20) DEFAULT NULL,
  `serial_no` varchar(20) DEFAULT NULL,
  `status` enum('received','not yet') DEFAULT NULL,
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`ordering`)
);
