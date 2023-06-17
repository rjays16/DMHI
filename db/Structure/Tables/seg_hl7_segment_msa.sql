
DROP TABLE IF EXISTS `seg_hl7_segment_msa`;
CREATE TABLE `seg_hl7_segment_msa` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  PRIMARY KEY (`filename`)
);
