
DROP TABLE IF EXISTS `seg_social_patient`;
CREATE TABLE `seg_social_patient` (
  `pid` varchar(12) NOT NULL DEFAULT '',
  `mss_no` varchar(12) NOT NULL DEFAULT '',
  `status` varchar(25) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NULL DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pid`,`mss_no`),
  KEY `FK_seg_social_patient` (`mss_no`),
  CONSTRAINT `FK_seg_social_patient` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
