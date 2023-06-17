
DROP TABLE IF EXISTS `seg_social_no_problem`;
CREATE TABLE `seg_social_no_problem` (
  `pid` int(11) NOT NULL,
  `encounter_nr` int(20) NOT NULL,
  `no_social_problem` int(11) DEFAULT NULL,
  PRIMARY KEY (`pid`,`encounter_nr`)
);
