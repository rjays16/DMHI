
DROP TABLE IF EXISTS `seg_etransmittal_log`;
CREATE TABLE `seg_etransmittal_log` (
  `log_id` char(36) NOT NULL,
  `transmit_no` varchar(20) NOT NULL,
  `etransmit_no` varchar(30) NOT NULL,
  `transmission_control_no` varchar(20) DEFAULT NULL,
  `transmission_time` time DEFAULT NULL,
  `transmission_dte` date DEFAULT NULL,
  `receipt_ticketno` varchar(20) DEFAULT NULL,
  `hospital_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
);
