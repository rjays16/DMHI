
DROP TABLE IF EXISTS `seg_cert_reinstatement`;
CREATE TABLE `seg_cert_reinstatement` (
  `encounter_nr` varchar(12) NOT NULL,
  `purpose` text,
  `dr_nr` int(11) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`)
);
