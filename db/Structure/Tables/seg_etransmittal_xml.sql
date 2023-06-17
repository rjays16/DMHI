
DROP TABLE IF EXISTS `seg_etransmittal_xml`;
CREATE TABLE `seg_etransmittal_xml` (
  `xml_id` bigint(20) NOT NULL,
  `encounter_nr` varchar(20) DEFAULT NULL,
  `xml_docstring` blob,
  `xml_isvalid` int(1) DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `create_id` varchar(20) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `modify_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`xml_id`),
  KEY `encounter_nr` (`encounter_nr`),
  CONSTRAINT `encounter_nr` FOREIGN KEY (`encounter_nr`) REFERENCES `care_encounter` (`encounter_nr`)
);
