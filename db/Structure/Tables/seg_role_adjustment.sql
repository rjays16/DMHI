
DROP TABLE IF EXISTS `seg_role_adjustment`;
CREATE TABLE `seg_role_adjustment` (
  `role_area` enum('D1','D2','D3','D4') NOT NULL,
  `adjust_rate` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `bsked_id` int(11) unsigned NOT NULL,
  `range_start` int(4) unsigned NOT NULL DEFAULT '0',
  `confinetype_id` smallint(5) NOT NULL,
  PRIMARY KEY (`role_area`,`bsked_id`,`range_start`,`confinetype_id`)
);
