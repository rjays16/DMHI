
DROP TABLE IF EXISTS `seg_occupation`;
CREATE TABLE `seg_occupation` (
  `occupation_nr` int(11) NOT NULL,
  `occupation_name` varchar(50) NOT NULL,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_date` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`occupation_nr`)
);
