
DROP TABLE IF EXISTS `seg_social_house_type`;
CREATE TABLE `seg_social_house_type` (
  `house_type_nr` int(5) unsigned NOT NULL,
  `house_description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`house_type_nr`)
);
