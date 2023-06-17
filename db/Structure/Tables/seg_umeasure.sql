
DROP TABLE IF EXISTS `seg_umeasure`;
CREATE TABLE `seg_umeasure` (
  `unit_id` int(10) unsigned NOT NULL,
  `unit_name` varchar(5) NOT NULL,
  `unit_desc` varchar(25) NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`unit_id`),
  KEY `FK_seg_umeasure` (`create_id`),
  KEY `FK_seg_umeasure_modify_id` (`modify_id`),
  CONSTRAINT `seg_umeasure_ibfk_1` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE,
  CONSTRAINT `seg_umeasure_ibfk_2` FOREIGN KEY (`modify_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE
);
