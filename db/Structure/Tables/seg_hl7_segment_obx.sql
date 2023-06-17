
DROP TABLE IF EXISTS `seg_hl7_segment_obx`;
CREATE TABLE `seg_hl7_segment_obx` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  PRIMARY KEY (`filename`)
);
