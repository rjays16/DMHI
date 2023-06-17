
DROP TABLE IF EXISTS `seg_social_lingap`;
CREATE TABLE `seg_social_lingap` (
  `pid` varchar(12) NOT NULL,
  `date_generated` date NOT NULL,
  `control_nr` varchar(10) NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`pid`,`date_generated`),
  UNIQUE KEY `NewIndex1` (`control_nr`),
  KEY `FK_seg_social_lingap` (`create_id`),
  CONSTRAINT `FK_seg_social_lingap` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON DELETE SET NULL ON UPDATE CASCADE
);
