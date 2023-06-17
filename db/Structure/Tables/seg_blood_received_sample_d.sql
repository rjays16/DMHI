
DROP TABLE IF EXISTS `seg_blood_received_sample_d`;
CREATE TABLE `seg_blood_received_sample_d` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `qty_ordered` decimal(2,0) DEFAULT NULL,
  `qty_received` decimal(2,0) DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`),
  KEY `FK_seg_blood_received_sample_d_code` (`service_code`),
  CONSTRAINT `FK_seg_blood_received_sample_d` FOREIGN KEY (`refno`) REFERENCES `seg_blood_received_sample_h` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_blood_received_sample_d_code` FOREIGN KEY (`service_code`) REFERENCES `seg_lab_services` (`service_code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
