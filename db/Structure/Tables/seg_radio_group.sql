
DROP TABLE IF EXISTS `seg_radio_group`;
CREATE TABLE `seg_radio_group` (
  `service_code` varchar(10) NOT NULL,
  `service_code_child` varchar(10) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(300) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(300) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`service_code`,`service_code_child`)
);
