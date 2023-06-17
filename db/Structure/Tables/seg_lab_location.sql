
DROP TABLE IF EXISTS `seg_lab_location`;
CREATE TABLE `seg_lab_location` (
  `loc_code` varchar(10) NOT NULL,
  `loc_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`loc_code`)
);
