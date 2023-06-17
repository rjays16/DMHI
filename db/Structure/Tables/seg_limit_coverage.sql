
DROP TABLE IF EXISTS `seg_limit_coverage`;
CREATE TABLE `seg_limit_coverage` (
  `encounter_nr` varchar(25) NOT NULL,
  `pid` varchar(25) NOT NULL,
  `med_amount` int(25) NOT NULL,
  `rad_amount` int(25) NOT NULL,
  `lab_amount` int(25) NOT NULL,
  `max_coverage` int(25) NOT NULL,
  `is_deleted` int(5) NOT NULL
);
