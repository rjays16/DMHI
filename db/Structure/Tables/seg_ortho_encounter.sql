
DROP TABLE IF EXISTS `seg_ortho_encounter`;
CREATE TABLE `seg_ortho_encounter` (
  `hrn` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `encounter_type` varchar(10) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `admission_date` varchar(20) DEFAULT NULL,
  `diagnosis` text,
  `discharge_date` varchar(20) DEFAULT NULL,
  `result` varchar(50) DEFAULT NULL,
  `disposition` varchar(50) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL
);
