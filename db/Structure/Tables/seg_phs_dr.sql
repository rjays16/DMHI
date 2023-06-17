
DROP TABLE IF EXISTS `seg_phs_dr`;
CREATE TABLE `seg_phs_dr` (
  `dr_nr` int(11) NOT NULL,
  `dept_nr` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`dr_nr`,`dept_nr`)
);
