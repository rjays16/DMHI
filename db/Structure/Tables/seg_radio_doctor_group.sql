
DROP TABLE IF EXISTS `seg_radio_doctor_group`;
CREATE TABLE `seg_radio_doctor_group` (
  `group_nr` int(11) NOT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`group_nr`)
);
