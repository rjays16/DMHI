
DROP TABLE IF EXISTS `care_encounter_sickconfirm`;
CREATE TABLE `care_encounter_sickconfirm` (
  `nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '0',
  `date_confirm` date NOT NULL DEFAULT '0000-00-00',
  `date_start` date NOT NULL DEFAULT '0000-00-00',
  `date_end` date NOT NULL DEFAULT '0000-00-00',
  `date_create` date NOT NULL DEFAULT '0000-00-00',
  `diagnosis` text NOT NULL,
  `dept_nr` smallint(6) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `encounter_nr` (`encounter_nr`),
  CONSTRAINT `FK_care_encounter_sickconfirm` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
