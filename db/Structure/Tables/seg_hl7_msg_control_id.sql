
DROP TABLE IF EXISTS `seg_hl7_msg_control_id`;
CREATE TABLE `seg_hl7_msg_control_id` (
  `msg_control_id` varchar(20) NOT NULL,
  `dept` enum('LB','RD') NOT NULL,
  PRIMARY KEY (`msg_control_id`,`dept`)
);
