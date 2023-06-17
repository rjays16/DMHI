
DROP TABLE IF EXISTS `seg_hl7_segment_pid`;
CREATE TABLE `seg_hl7_segment_pid` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  `pid` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`filename`)
);
