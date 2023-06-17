
DROP TABLE IF EXISTS `seg_hl7_segment_obr`;
CREATE TABLE `seg_hl7_segment_obr` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  `lis_order_no` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`filename`)
);
