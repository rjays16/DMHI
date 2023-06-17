
DROP TABLE IF EXISTS `care_encounter_tracker`;
CREATE TABLE `care_encounter_tracker` (
  `triage` varchar(20) NOT NULL,
  `last_encounter_nr` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`triage`)
);
