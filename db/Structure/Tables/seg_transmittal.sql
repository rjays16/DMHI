
DROP TABLE IF EXISTS `seg_transmittal`;
CREATE TABLE `seg_transmittal` (
  `transmit_no` varchar(14) NOT NULL,
  `transmit_dte` datetime NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `remarks` text NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`transmit_no`),
  KEY `FK_seg_transmittal_insurance_firm` (`hcare_id`),
  CONSTRAINT `FK_seg_transmittal_insurance_firm` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE
);
