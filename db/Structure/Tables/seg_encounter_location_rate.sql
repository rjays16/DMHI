
DROP TABLE IF EXISTS `seg_encounter_location_rate`;
CREATE TABLE `seg_encounter_location_rate` (
  `loc_enc_nr` int(11) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL DEFAULT '',
  `ward_nr` smallint(5) unsigned NOT NULL,
  `room_nr` mediumint(8) unsigned NOT NULL,
  `bed_nr` smallint(5) unsigned NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`loc_enc_nr`,`encounter_nr`),
  KEY `FK_seg_encounter_location_rate_encounter` (`encounter_nr`),
  CONSTRAINT `FK_seg_encounter_location_rate_encounter` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
