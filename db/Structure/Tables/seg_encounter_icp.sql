
DROP TABLE IF EXISTS `seg_encounter_icp`;
CREATE TABLE `seg_encounter_icp` (
  `encounter_nr` varchar(12) NOT NULL,
  `procedure_code` varchar(15) NOT NULL,
  `status` varchar(25) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`procedure_code`),
  KEY `FK_seg_encounter_icp` (`procedure_code`),
  CONSTRAINT `FK_seg_encounter_icp` FOREIGN KEY (`procedure_code`) REFERENCES `care_ops301_en` (`code`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_encounter_icp_1` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`) ON UPDATE CASCADE
);
