
DROP TABLE IF EXISTS `seg_issuance_type`;
CREATE TABLE `seg_issuance_type` (
  `iss_type_id` varchar(15) NOT NULL,
  `iss_type_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iss_type_id`)
);
