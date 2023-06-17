
DROP TABLE IF EXISTS `seg_social_problems_patient`;
CREATE TABLE `seg_social_problems_patient` (
  `pid` varchar(12) NOT NULL,
  `encounter_nr` varchar(20) NOT NULL,
  `problems_fxn_id` varchar(100) NOT NULL,
  `severity_id` int(11) DEFAULT NULL,
  `duration_id` int(11) DEFAULT NULL,
  `others` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pid`,`encounter_nr`,`problems_fxn_id`)
);
