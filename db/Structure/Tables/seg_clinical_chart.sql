
DROP TABLE IF EXISTS `seg_clinical_chart`;
CREATE TABLE `seg_clinical_chart` (
  `encounter_nr` varchar(12) NOT NULL,
  `record_date` text,
  `hospital_days` text,
  `day_po_pp` text,
  `temperature` text,
  `pulse` text,
  `respirations` text,
  `blood_pressure` text,
  `weight` text,
  `intake_oral` text,
  `parenteral` text,
  `output_urine` text,
  `drainage` text,
  `emesis` text,
  `stools` text,
  PRIMARY KEY (`encounter_nr`)
);
