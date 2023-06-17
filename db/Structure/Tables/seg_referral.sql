
DROP TABLE IF EXISTS `seg_referral`;
CREATE TABLE `seg_referral` (
  `referral_nr` varchar(12) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `referrer_diagnosis` tinytext,
  `referrer_dr` varchar(100) NOT NULL,
  `referrer_dept` varchar(255) NOT NULL,
  `referrer_notes` text,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_referral` tinyint(1) NOT NULL,
  `referral_date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `cancel_reason` text,
  `is_dept` tinyint(1) NOT NULL DEFAULT '1',
  `reason_referral_nr` varchar(10) DEFAULT NULL,
  `refer_hospital_nr` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`referral_nr`),
  KEY `FK_seg_referral` (`encounter_nr`),
  CONSTRAINT `FK_seg_referral` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE NO ACTION ON UPDATE CASCADE
);
