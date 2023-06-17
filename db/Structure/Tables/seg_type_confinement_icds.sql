
DROP TABLE IF EXISTS `seg_type_confinement_icds`;
CREATE TABLE `seg_type_confinement_icds` (
  `confinetype_id` smallint(5) unsigned NOT NULL,
  `diagnosis_code` varchar(15) NOT NULL,
  `paired_codes` text NOT NULL,
  PRIMARY KEY (`confinetype_id`,`diagnosis_code`),
  CONSTRAINT `FK_seg_type_confinement_icds_type_confinement` FOREIGN KEY (`confinetype_id`) REFERENCES `seg_type_confinement` (`confinetype_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
