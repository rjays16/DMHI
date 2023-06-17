
DROP TABLE IF EXISTS `seg_lab_result`;
CREATE TABLE `seg_lab_result` (
  `result_id` int(10) unsigned NOT NULL,
  `refno` varchar(12) NOT NULL,
  `param_id` smallint(5) NOT NULL,
  `result_value` text,
  `unit` varchar(15) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`result_id`,`refno`,`param_id`),
  KEY `FK_seg_lab_result` (`refno`),
  CONSTRAINT `FK_seg_lab_result` FOREIGN KEY (`refno`) REFERENCES `seg_lab_resultdata` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
