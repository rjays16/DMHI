
DROP TABLE IF EXISTS `seg_ops_chrg_dr`;
CREATE TABLE `seg_ops_chrg_dr` (
  `encounter_nr` varchar(12) NOT NULL DEFAULT '',
  `dr_nr` int(11) NOT NULL,
  `dr_role_type_nr` smallint(5) unsigned NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `role_type_level` int(10) unsigned DEFAULT NULL,
  `ops_refno` varchar(12) NOT NULL,
  `ops_entryno` smallint(5) unsigned NOT NULL,
  `ops_code` varchar(12) NOT NULL,
  `rvu` int(10) unsigned NOT NULL,
  `multiplier` decimal(10,4) NOT NULL,
  PRIMARY KEY (`encounter_nr`,`dr_nr`,`dr_role_type_nr`,`entry_no`,`ops_refno`,`ops_entryno`,`ops_code`)
);
