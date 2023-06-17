
DROP TABLE IF EXISTS `seg_ortho_patient_type`;
CREATE TABLE `seg_ortho_patient_type` (
  `id` int(2) NOT NULL,
  `patient_type` varchar(50) DEFAULT NULL,
  `encounter_type` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
