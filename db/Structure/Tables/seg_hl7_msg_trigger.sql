
DROP TABLE IF EXISTS `seg_hl7_msg_trigger`;
CREATE TABLE `seg_hl7_msg_trigger` (
  `event_id` varchar(5) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `msg_type_id` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `FK_seg_hl7_msg_trigger_type` (`msg_type_id`),
  CONSTRAINT `FK_seg_hl7_msg_trigger_type` FOREIGN KEY (`msg_type_id`) REFERENCES `seg_hl7_msg_type` (`msg_type_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
