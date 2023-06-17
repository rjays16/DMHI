
DROP TABLE IF EXISTS `seg_diagnosis_procedure`;
CREATE TABLE `seg_diagnosis_procedure` (
  `diagnosis_nr` int(11) NOT NULL,
  `procedure_nr` int(11) NOT NULL,
  PRIMARY KEY (`diagnosis_nr`,`procedure_nr`)
);
