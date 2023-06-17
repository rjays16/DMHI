
DROP TABLE IF EXISTS `seg_billing_encounter_details`;
CREATE TABLE `seg_billing_encounter_details` (
  `bill_nr` varchar(12) NOT NULL,
  `refno` varchar(12) NOT NULL DEFAULT '0000000',
  `ref_area` enum('LB','RD','SU','MS','OA','PH','OR','NONE') NOT NULL,
  PRIMARY KEY (`bill_nr`,`refno`,`ref_area`),
  KEY `idx_bill_nr` (`bill_nr`),
  KEY `idx_area` (`ref_area`),
  KEY `idx_request` (`refno`),
  CONSTRAINT `FK_bill_nr_details` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
