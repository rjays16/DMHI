
DROP TABLE IF EXISTS `seg_encounter_case`;
CREATE TABLE `seg_encounter_case` (
  `encounter_nr` varchar(12) NOT NULL,
  `casetype_id` smallint(5) unsigned NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`casetype_id`,`modify_dt`),
  KEY `FK_seg_encounter_case_type` (`casetype_id`),
  CONSTRAINT `FK_seg_encounter_case_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_case_type` FOREIGN KEY (`casetype_id`) REFERENCES `seg_type_case` (`casetype_id`) ON UPDATE CASCADE
);
