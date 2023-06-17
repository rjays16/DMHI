
DROP TABLE IF EXISTS `seg_type_case`;
CREATE TABLE `seg_type_case` (
  `casetype_id` smallint(5) unsigned NOT NULL,
  `casetype_desc` varchar(80) NOT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`casetype_id`)
);
