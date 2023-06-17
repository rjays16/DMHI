
DROP TABLE IF EXISTS `seg_personell_nurse_area`;
CREATE TABLE `seg_personell_nurse_area` (
  `personell_nr` int(11) NOT NULL,
  `ward_nr` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`personell_nr`,`ward_nr`)
);
