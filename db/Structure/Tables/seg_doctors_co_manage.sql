
DROP TABLE IF EXISTS `seg_doctors_co_manage`;
CREATE TABLE `seg_doctors_co_manage` (
  `encounter_nr` varchar(12) NOT NULL,
  `doctor_nr` varchar(12) NOT NULL,
  `modify_id` varchar(50) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(50) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `history` text,
  `is_deleted` tinyint(1) DEFAULT '0'
);
