
DROP TABLE IF EXISTS `seg_blood_program_partner`;
CREATE TABLE `seg_blood_program_partner` (
  `code` varchar(12) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`code`)
);
