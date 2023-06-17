
DROP TABLE IF EXISTS `seg_educational_attainment`;
CREATE TABLE `seg_educational_attainment` (
  `educ_attain_nr` int(11) NOT NULL,
  `educ_attain_name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `modify_id` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `modify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`educ_attain_nr`)
);
