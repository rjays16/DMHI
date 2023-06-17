
DROP TABLE IF EXISTS `seg_vehicle_driven`;
CREATE TABLE `seg_vehicle_driven` (
  `vehicle_drive_nr` int(2) unsigned NOT NULL,
  `vehicle_drive_name` varchar(50) DEFAULT '',
  PRIMARY KEY (`vehicle_drive_nr`)
);
