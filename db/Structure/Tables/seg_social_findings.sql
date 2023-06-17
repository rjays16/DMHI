
DROP TABLE IF EXISTS `seg_social_findings`;
CREATE TABLE `seg_social_findings` (
  `pid` int(11) NOT NULL,
  `encounter_nr` varchar(20) NOT NULL,
  `problem_presented` varchar(100) DEFAULT NULL,
  `other_problem` varchar(100) DEFAULT NULL,
  `counseling_done` int(11) DEFAULT NULL,
  `topic_concern` varchar(100) DEFAULT NULL,
  `no_reason` varchar(500) DEFAULT NULL,
  `social_diagnosis` varchar(500) DEFAULT NULL,
  `intervention` varchar(500) DEFAULT NULL,
  `action_taken` varchar(500) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`pid`,`encounter_nr`)
);
