
DROP TABLE IF EXISTS `seg_encounter_sservice`;
CREATE TABLE `seg_encounter_sservice` (
  `encounter_nr` varchar(12) NOT NULL,
  `service_code` int(5) NOT NULL,
  `ss_notes` varchar(50) NOT NULL,
  `ss_history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`),
  CONSTRAINT `FK_seg_encounter_sservice_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
