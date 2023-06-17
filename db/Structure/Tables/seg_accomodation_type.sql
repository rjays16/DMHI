
DROP TABLE IF EXISTS `seg_accomodation_type`;
CREATE TABLE `seg_accomodation_type` (
  `accomodation_nr` smallint(6) NOT NULL,
  `accomodation_name` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`accomodation_nr`)
);
