
DROP TABLE IF EXISTS `seg_social_patient_family`;
CREATE TABLE `seg_social_patient_family` (
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(20) DEFAULT NULL,
  `dependent_id` varchar(12) DEFAULT NULL,
  `dependent_name` text,
  `dependent_age` smallint(5) DEFAULT NULL,
  `dependent_status` text,
  `relation_to_patient` text,
  `dep_educ_attainment` text,
  `dependent_occupation` text,
  `dep_monthly_income` varchar(30) DEFAULT NULL,
  KEY `pid` (`pid`)
);
