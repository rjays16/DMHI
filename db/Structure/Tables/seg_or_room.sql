
DROP TABLE IF EXISTS `seg_or_room`;
CREATE TABLE `seg_or_room` (
  `dept_nr` int(5) unsigned NOT NULL,
  `room_nr` int(5) unsigned NOT NULL,
  `room_name` varchar(50) DEFAULT NULL,
  `description` text,
  `max_pxn` int(5) DEFAULT NULL,
  PRIMARY KEY (`dept_nr`,`room_nr`)
);
