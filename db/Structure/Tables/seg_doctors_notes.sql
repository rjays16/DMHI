
DROP TABLE IF EXISTS `seg_doctors_notes`;
CREATE TABLE `seg_doctors_notes` (
  `personell_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `chief_complaint` text,
  `physical_examination` text,
  `clinical_summary` text,
  `history` text,
  `create_id` varchar(12) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`personell_nr`,`encounter_nr`),
  KEY `FK_seg_doctors_notes_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_doctors_notes_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_doctors_notes_personell` FOREIGN KEY (`personell_nr`) REFERENCES `care_personell` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
