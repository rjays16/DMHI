
DROP TABLE IF EXISTS `seg_source_income`;
CREATE TABLE `seg_source_income` (
  `source_income_id` int(11) NOT NULL DEFAULT '0',
  `source_income_desc` varchar(50) DEFAULT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`source_income_id`)
);
