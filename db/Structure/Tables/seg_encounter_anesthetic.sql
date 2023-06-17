
DROP TABLE IF EXISTS `seg_encounter_anesthetic`;
CREATE TABLE `seg_encounter_anesthetic` (
  `anesthesia_care_id` bigint(20) unsigned NOT NULL,
  `or_main_refno` bigint(20) unsigned DEFAULT NULL,
  `order_refno` varchar(12) DEFAULT NULL,
  `anesthetic_id` varchar(25) DEFAULT NULL,
  KEY `FK_seg_encounter_anesthetic` (`anesthesia_care_id`),
  CONSTRAINT `FK_seg_encounter_anesthetic` FOREIGN KEY (`anesthesia_care_id`) REFERENCES `seg_encounter_anesthesia` (`anesthesia_care_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
