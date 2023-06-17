
DROP TABLE IF EXISTS `seg_insurance_member_info`;
CREATE TABLE `seg_insurance_member_info` (
  `pid` varchar(12) NOT NULL,
  `hcare_id` int(8) unsigned NOT NULL,
  `insurance_nr` varchar(25) NOT NULL,
  `member_lname` varchar(150) DEFAULT NULL,
  `member_fname` varchar(150) DEFAULT NULL,
  `member_mname` varchar(150) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `street_name` varchar(150) DEFAULT NULL,
  `brgy_nr` int(11) unsigned DEFAULT NULL,
  `mun_nr` int(11) unsigned DEFAULT NULL,
  `relation` char(1) DEFAULT NULL,
  `member_type` varchar(5) DEFAULT NULL,
  `employer_no` varchar(25) DEFAULT NULL,
  `employer_name` varchar(150) DEFAULT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`,`hcare_id`,`insurance_nr`),
  KEY `FK_seg_insurance_member_info_insurance` (`hcare_id`),
  KEY `FK_seg_insurance_member_info_barangay` (`brgy_nr`),
  KEY `FK_seg_insurance_member_info_municity` (`mun_nr`),
  CONSTRAINT `FK_seg_insurance_member_info_barangay` FOREIGN KEY (`brgy_nr`) REFERENCES `seg_barangays` (`brgy_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_insurance_member_info_insurance` FOREIGN KEY (`hcare_id`) REFERENCES `care_insurance_firm` (`hcare_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_insurance_member_info_municity` FOREIGN KEY (`mun_nr`) REFERENCES `seg_municity` (`mun_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_insurance_member_info_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
