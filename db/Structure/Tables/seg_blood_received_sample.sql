
DROP TABLE IF EXISTS `seg_blood_received_sample`;
CREATE TABLE `seg_blood_received_sample` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `ordered_qty` decimal(2,0) DEFAULT NULL,
  `received_qty` decimal(2,0) DEFAULT NULL,
  `status` enum('complete','lack','none') DEFAULT NULL,
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`)
);
