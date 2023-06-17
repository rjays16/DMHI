
DROP TABLE IF EXISTS `seg_inventory_reptbl`;
CREATE TABLE `seg_inventory_reptbl` (
  `rep_nr` int(4) unsigned NOT NULL,
  `rep_name` varchar(80) NOT NULL,
  `rep_script` varchar(64) NOT NULL,
  PRIMARY KEY (`rep_nr`)
);
