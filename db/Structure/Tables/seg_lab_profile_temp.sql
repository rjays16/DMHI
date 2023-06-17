
DROP TABLE IF EXISTS `seg_lab_profile_temp`;
CREATE TABLE `seg_lab_profile_temp` (
  `group_code` varchar(20) DEFAULT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `service_code` varchar(20) DEFAULT NULL,
  `service_name` varchar(100) DEFAULT NULL,
  `service_child_code` varchar(20) DEFAULT NULL,
  `service_child_name` varchar(100) DEFAULT NULL
);
