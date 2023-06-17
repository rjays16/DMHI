
DROP TABLE IF EXISTS `seg_confinement_tracker`;
CREATE TABLE `seg_confinement_tracker` (
  `pid` varchar(12) NOT NULL,
  `current_year` year(4) NOT NULL,
  `bill_nr` varchar(12) NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `confine_days` int(10) unsigned NOT NULL,
  `principal_pid` varchar(12) NOT NULL,
  PRIMARY KEY (`pid`,`current_year`,`bill_nr`,`hcare_id`),
  KEY `FK_seg_confinement_tracker_principal_person` (`principal_pid`),
  KEY `FK_seg_confinement_tracker_billing_encounter` (`bill_nr`),
  KEY `FK_seg_confinement_tracker_insurance_firm` (`hcare_id`),
  KEY `pid_index` (`pid`),
  CONSTRAINT `FK_seg_confinement_tracker_billing_encounter` FOREIGN KEY (`bill_nr`) REFERENCES `seg_billing_encounter` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_confinement_tracker_insurance_firm` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_confinement_tracker_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_confinement_tracker_person_principal` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
