
DROP TABLE IF EXISTS `seg_social_functioning_patient`;
CREATE TABLE `seg_social_functioning_patient` (
  `pid` varchar(12) NOT NULL,
  `encounter_nr` varchar(20) NOT NULL,
  `social_fxn_id` varchar(50) NOT NULL,
  `interaction_id` int(11) DEFAULT NULL,
  `severity_id` int(11) DEFAULT NULL,
  `duration_id` int(11) DEFAULT NULL,
  `coping_id` int(11) DEFAULT NULL,
  `others` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`pid`,`encounter_nr`,`social_fxn_id`),
  KEY `pid` (`pid`,`social_fxn_id`)
);
