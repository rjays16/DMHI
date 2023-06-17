
DROP TABLE IF EXISTS `seg_encounter_anesthesia`;
CREATE TABLE `seg_encounter_anesthesia` (
  `anesthesia_care_id` bigint(20) unsigned NOT NULL,
  `or_main_refno` bigint(20) unsigned DEFAULT NULL,
  `anesthesia` smallint(2) unsigned NOT NULL,
  `anesthetics` text,
  `time_begun` time DEFAULT NULL,
  `time_ended` time DEFAULT NULL,
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `anesthesia_category` varchar(35) DEFAULT NULL,
  `anesthesia_specific` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`anesthesia_care_id`),
  KEY `FK_care_encounter_anesthesia2` (`anesthesia`),
  KEY `FK_seg_encounter_anesthesia` (`or_main_refno`),
  CONSTRAINT `FK_care_encounter_anesthesia2` FOREIGN KEY (`anesthesia`) REFERENCES `care_type_anaesthesia` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
