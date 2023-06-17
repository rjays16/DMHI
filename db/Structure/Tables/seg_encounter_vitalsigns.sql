
DROP TABLE IF EXISTS `seg_encounter_vitalsigns`;
CREATE TABLE `seg_encounter_vitalsigns` (
  `encounter_nr` varchar(12) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pid` varchar(12) NOT NULL,
  `systole` int(3) DEFAULT NULL,
  `diastole` int(3) DEFAULT NULL,
  `temp` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `resp_rate` double DEFAULT NULL,
  `pulse_rate` double DEFAULT NULL,
  `bp_unit` int(11) DEFAULT '5',
  `temp_unit` int(11) DEFAULT '1',
  `weight_unit` int(11) DEFAULT '6',
  `rr_unit` int(11) DEFAULT '4',
  `pr_unit` int(11) DEFAULT '3',
  `history` text,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vitalsign_no` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`vitalsign_no`),
  KEY `encounter_nr` (`encounter_nr`),
  KEY `pid` (`pid`)
);
