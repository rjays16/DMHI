
DROP TABLE IF EXISTS `seg_eclaims_verification_log`;
CREATE TABLE `seg_eclaims_verification_log` (
  `encounter_nr` varchar(12) NOT NULL,
  `tracking_no` varchar(12) DEFAULT NULL,
  `verification_time` datetime DEFAULT NULL,
  `is_ok` tinyint(1) DEFAULT '0',
  `remaining_days` smallint(6) DEFAULT NULL,
  `ref_date` date DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`),
  CONSTRAINT `seg_eclaims_verification_log_ibfk_1` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
