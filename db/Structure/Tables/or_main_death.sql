
DROP TABLE IF EXISTS `or_main_death`;
CREATE TABLE `or_main_death` (
  `or_main_refno` bigint(20) unsigned NOT NULL,
  `date_time_of_death` timestamp NULL DEFAULT NULL,
  `cause_of_death` text,
  `patient_classification` tinyint(4) DEFAULT NULL,
  `death_time_range` tinyint(4) DEFAULT NULL,
  `history` text,
  `created_id` varchar(35) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `modified_id` varchar(35) DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`or_main_refno`)
);
