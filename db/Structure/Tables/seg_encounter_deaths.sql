
DROP TABLE IF EXISTS `seg_encounter_deaths`;
CREATE TABLE `seg_encounter_deaths` (
  `encounter_nr` varchar(10) NOT NULL,
  `ward_nr` varchar(20) DEFAULT NULL,
  `is_beyond_48hrs` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`encounter_nr`)
);
