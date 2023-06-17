
DROP TABLE IF EXISTS `seg_cert_employment`;
CREATE TABLE `seg_cert_employment` (
  `encounter_nr` varchar(12) NOT NULL,
  `proposed_position` text,
  `agency` text,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `dr_nr` int(11) DEFAULT NULL,
  `height_unit` varchar(20) DEFAULT NULL,
  `weight_unit` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`)
);
