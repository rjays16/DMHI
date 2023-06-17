
DROP TABLE IF EXISTS `seg_opd_or_temp`;
CREATE TABLE `seg_opd_or_temp` (
  `or_id` int(11) NOT NULL,
  `or_desc` varchar(20) DEFAULT NULL,
  `modify_id` varchar(20) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`or_id`)
);
