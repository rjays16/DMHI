
DROP TABLE IF EXISTS `seg_cert_med`;
CREATE TABLE `seg_cert_med` (
  `encounter_nr` varchar(12) NOT NULL,
  `diagnosis_verbatim` text,
  `procedure_verbatim` text,
  `is_medico_legal` int(1) DEFAULT '1',
  `is_doc_sig` int(1) DEFAULT '0',
  `dr_nr` varchar(300) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(50) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `consultation_date` date DEFAULT NULL,
  `referral_nr` varchar(12) DEFAULT NULL,
  `cert_nr` int(12) unsigned NOT NULL,
  PRIMARY KEY (`cert_nr`)
);
