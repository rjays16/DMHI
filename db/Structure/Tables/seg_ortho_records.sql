
DROP TABLE IF EXISTS `seg_ortho_records`;
CREATE TABLE `seg_ortho_records` (
  `hrn` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `patient_name` varchar(100) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `admission_date` varchar(20) DEFAULT NULL,
  `inquiry_date` varchar(20) DEFAULT NULL,
  `diagnosis` text,
  `procedure` text,
  `surgery_date` varchar(20) DEFAULT NULL,
  `operating_surgeons` varchar(200) DEFAULT NULL,
  `nature_surgery` varchar(20) DEFAULT NULL,
  `type_surgery` varchar(20) DEFAULT NULL,
  `ruv_code` varchar(200) DEFAULT NULL,
  `specialty` varchar(20) DEFAULT NULL,
  `doctor` varchar(20) DEFAULT NULL,
  `discharge_date` varchar(20) DEFAULT NULL,
  `result` varchar(50) DEFAULT NULL,
  `disposition` varchar(50) DEFAULT NULL,
  `service_surgeons` text,
  `category` varchar(20) DEFAULT NULL
);
