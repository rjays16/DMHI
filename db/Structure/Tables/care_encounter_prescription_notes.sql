
DROP TABLE IF EXISTS `care_encounter_prescription_notes`;
CREATE TABLE `care_encounter_prescription_notes` (
  `nr` int(11) unsigned NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `prescription_nr` int(10) unsigned NOT NULL DEFAULT '0',
  `notes` varchar(35) DEFAULT NULL,
  `short_notes` varchar(25) DEFAULT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
