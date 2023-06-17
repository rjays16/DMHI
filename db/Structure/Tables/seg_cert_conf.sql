
DROP TABLE IF EXISTS `seg_cert_conf`;
CREATE TABLE `seg_cert_conf` (
  `encounter_nr` varchar(12) NOT NULL,
  `is_vehicular_accident` int(1) DEFAULT NULL,
  `is_medico_legal` int(1) DEFAULT '1',
  `is_doc_sig` int(1) DEFAULT '0',
  `dr_nr` int(11) DEFAULT NULL,
  `nurse_on_duty` int(11) DEFAULT NULL,
  `attending_doctor` text,
  `purpose` text,
  `requested_by` varchar(250) DEFAULT NULL,
  `relation_to_patient` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`)
);
