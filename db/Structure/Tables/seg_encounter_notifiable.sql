
DROP TABLE IF EXISTS `seg_encounter_notifiable`;
CREATE TABLE `seg_encounter_notifiable` (
  `encounter_nr` varchar(12) NOT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `code_illness` varchar(12) NOT NULL,
  `remarks` text,
  PRIMARY KEY (`encounter_nr`,`code_illness`)
);
