
DROP TABLE IF EXISTS `seg_hl7_segment_nte`;
CREATE TABLE `seg_hl7_segment_nte` (
  `filename` varchar(100) NOT NULL,
  `message` text,
  `obx_order` smallint(3) NOT NULL,
  PRIMARY KEY (`filename`,`obx_order`)
);
