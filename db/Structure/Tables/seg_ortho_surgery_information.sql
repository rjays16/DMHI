
DROP TABLE IF EXISTS `seg_ortho_surgery_information`;
CREATE TABLE `seg_ortho_surgery_information` (
  `injury_id` int(6) NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `inquiry_date` datetime DEFAULT NULL,
  `diagnosis` blob,
  PRIMARY KEY (`injury_id`),
  UNIQUE KEY `NewIndex1` (`encounter_nr`,`inquiry_date`,`injury_id`)
);
