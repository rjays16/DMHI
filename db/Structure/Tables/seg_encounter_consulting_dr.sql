
DROP TABLE IF EXISTS `seg_encounter_consulting_dr`;
CREATE TABLE `seg_encounter_consulting_dr` (
  `encounter_nr` varchar(12) NOT NULL,
  `consulting_dr` int(11) NOT NULL,
  `consulting_dept` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`consulting_dr`)
);
