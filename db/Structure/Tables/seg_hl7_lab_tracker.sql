
DROP TABLE IF EXISTS `seg_hl7_lab_tracker`;
CREATE TABLE `seg_hl7_lab_tracker` (
  `msg_control_id` varchar(20) NOT NULL,
  `lis_order_no` varchar(20) NOT NULL,
  `msg_type` varchar(5) NOT NULL,
  `event_id` varchar(5) NOT NULL,
  `refno` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `hl7_msg` text,
  `create_date` timestamp NULL DEFAULT NULL,
  `modify_date` timestamp NULL DEFAULT NULL,
  `status` enum('ack','pending') DEFAULT 'pending',
  PRIMARY KEY (`msg_control_id`),
  KEY `FK_seg_hl7_lab_tracker_msgtype` (`msg_type`),
  KEY `FK_seg_hl7_lab_tracker_event` (`event_id`),
  KEY `refno_index` (`refno`),
  KEY `encounter_nr` (`encounter_nr`),
  KEY `pid` (`pid`),
  KEY `lis_order_no` (`lis_order_no`),
  CONSTRAINT `FK_seg_hl7_lab_tracker_event` FOREIGN KEY (`event_id`) REFERENCES `seg_hl7_msg_trigger` (`event_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
