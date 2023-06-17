
DROP TABLE IF EXISTS `seg_transmittal_details`;
CREATE TABLE `seg_transmittal_details` (
  `transmit_no` varchar(14) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '',
  `patient_claim` double(10,4) NOT NULL DEFAULT '0.0000',
  `is_rejected` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`transmit_no`,`encounter_nr`),
  KEY `FK_seg_transmittal_details_billing_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_transmittal_details_care_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_transmittal_details_header` FOREIGN KEY (`transmit_no`) REFERENCES `seg_transmittal` (`transmit_no`) ON DELETE CASCADE ON UPDATE CASCADE
);
