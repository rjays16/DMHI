
DROP TABLE IF EXISTS `seg_or_room_bed`;
CREATE TABLE `seg_or_room_bed` (
  `room_nr` int(5) unsigned NOT NULL,
  `bed_nr` int(5) NOT NULL,
  `description` text,
  PRIMARY KEY (`room_nr`,`bed_nr`)
);
