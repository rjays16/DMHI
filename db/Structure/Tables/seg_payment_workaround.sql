
DROP TABLE IF EXISTS `seg_payment_workaround`;
CREATE TABLE `seg_payment_workaround` (
  `service_area` enum('LB','RD','PH') NOT NULL,
  `refno` varchar(12) NOT NULL,
  `control_no` varchar(20) DEFAULT NULL,
  `approved_by` varchar(100) DEFAULT NULL,
  `type` enum('paid','lingap','cmap','phic') DEFAULT NULL,
  `reason` text,
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`service_area`,`refno`)
);
