
DROP TABLE IF EXISTS `seg_nurse_ward_area`;
CREATE TABLE `seg_nurse_ward_area` (
  `personell_nr` varchar(10) NOT NULL,
  `ward_nr` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`personell_nr`,`ward_nr`)
);
