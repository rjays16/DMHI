
DROP TABLE IF EXISTS `seg_blood_type_patient`;
CREATE TABLE `seg_blood_type_patient` (
  `pid` varchar(12) NOT NULL,
  `history` text,
  `blood_type` varchar(12) DEFAULT NULL,
  `create_id` varchar(60) DEFAULT NULL,
  `create_tm` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_tm` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `FK_seg_blood_type_patient` (`blood_type`),
  CONSTRAINT `FK_seg_blood_type_patient` FOREIGN KEY (`blood_type`) REFERENCES `seg_blood_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
