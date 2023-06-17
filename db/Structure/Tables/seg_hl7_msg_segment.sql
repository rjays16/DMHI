
DROP TABLE IF EXISTS `seg_hl7_msg_segment`;
CREATE TABLE `seg_hl7_msg_segment` (
  `msg_segment_id` varchar(5) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`msg_segment_id`)
);
