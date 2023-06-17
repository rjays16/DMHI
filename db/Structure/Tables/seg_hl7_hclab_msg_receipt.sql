
DROP TABLE IF EXISTS `seg_hl7_hclab_msg_receipt`;
CREATE TABLE `seg_hl7_hclab_msg_receipt` (
  `msg_control_id` varchar(20) NOT NULL,
  `lis_order_no` varchar(20) DEFAULT NULL,
  `msg_type_id` varchar(5) DEFAULT NULL,
  `event_id` varchar(5) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `hl7_msg` text,
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`msg_control_id`),
  CONSTRAINT `FK_seg_hl7_hclab_msg_receipt` FOREIGN KEY (`msg_control_id`) REFERENCES `seg_hl7_msg_control_id` (`msg_control_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
