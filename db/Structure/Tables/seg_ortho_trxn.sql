
DROP TABLE IF EXISTS `seg_ortho_trxn`;
CREATE TABLE `seg_ortho_trxn` (
  `encounter_nr` varchar(12) DEFAULT NULL,
  `inquiry_date` varchar(20) DEFAULT NULL,
  `procedure` text,
  `surgery_date` varchar(20) DEFAULT NULL,
  `operating_surgeons` varchar(200) DEFAULT NULL,
  `nature_surgery` varchar(20) DEFAULT NULL,
  `type_surgery` varchar(20) DEFAULT NULL,
  `specialty` varchar(20) DEFAULT NULL,
  `doctor` varchar(20) DEFAULT NULL,
  `service_surgeons` text,
  KEY `ortho_trxn_encounter_nr` (`encounter_nr`,`surgery_date`)
);
