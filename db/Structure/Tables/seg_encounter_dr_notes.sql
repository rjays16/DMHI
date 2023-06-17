
DROP TABLE IF EXISTS `seg_encounter_dr_notes`;
CREATE TABLE `seg_encounter_dr_notes` (
  `nr` int(11) unsigned NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `dr_nr` int(11) unsigned NOT NULL DEFAULT '0',
  `encounter_nr` varchar(12) DEFAULT NULL,
  `notes` text,
  `status` varchar(25) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
