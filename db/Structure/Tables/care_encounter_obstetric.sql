
DROP TABLE IF EXISTS `care_encounter_obstetric`;
CREATE TABLE `care_encounter_obstetric` (
  `encounter_nr` varchar(12) NOT NULL,
  `pregnancy_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `hospital_adm_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `patient_class` varchar(60) NOT NULL DEFAULT '',
  `is_discharged_not_in_labour` tinyint(1) DEFAULT NULL,
  `is_re_admission` tinyint(1) DEFAULT NULL,
  `referral_status` varchar(60) DEFAULT NULL,
  `referral_reason` text,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`),
  KEY `encounter_nr` (`pregnancy_nr`),
  CONSTRAINT `FK_care_encounter_obstetric` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
