
DROP TABLE IF EXISTS `seg_ortho_surgery_data`;
CREATE TABLE `seg_ortho_surgery_data` (
  `surgery_id` int(6) NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `date_of_surgery` datetime DEFAULT NULL,
  `procedure` blob,
  PRIMARY KEY (`surgery_id`)
);
