
DROP TABLE IF EXISTS `seg_medico_cases`;
CREATE TABLE `seg_medico_cases` (
  `code` varchar(10) NOT NULL,
  `medico_cases` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`code`)
);
