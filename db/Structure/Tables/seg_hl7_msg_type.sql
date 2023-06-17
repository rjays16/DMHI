
DROP TABLE IF EXISTS `seg_hl7_msg_type`;
CREATE TABLE `seg_hl7_msg_type` (
  `msg_type_id` varchar(5) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`msg_type_id`)
);
