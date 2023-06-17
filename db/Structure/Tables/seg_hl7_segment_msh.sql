
DROP TABLE IF EXISTS `seg_hl7_segment_msh`;
CREATE TABLE `seg_hl7_segment_msh` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  `msg_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`filename`)
);
