
DROP TABLE IF EXISTS `seg_religion`;
CREATE TABLE `seg_religion` (
  `religion_nr` int(11) NOT NULL,
  `religion_name` varchar(50) NOT NULL,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`religion_nr`)
);
