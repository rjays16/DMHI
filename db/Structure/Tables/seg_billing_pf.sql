
DROP TABLE IF EXISTS `seg_billing_pf`;
CREATE TABLE `seg_billing_pf` (
  `bill_nr` varchar(12) NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `dr_nr` int(11) NOT NULL,
  `role_area` enum('D1','D2','D3','D4') NOT NULL,
  `dr_charge` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `dr_claim` decimal(20,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`bill_nr`,`hcare_id`,`dr_nr`,`role_area`),
  KEY `FK_seg_billing_pf_personell` (`dr_nr`),
  KEY `FK_seg_billing_pf_hcares` (`hcare_id`),
  CONSTRAINT `FK_seg_billing_pf_billing_enc` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_billing_pf_hcares` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_billing_pf_personell` FOREIGN KEY (`dr_nr`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
