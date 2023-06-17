
DROP TABLE IF EXISTS `seg_type_confinement`;
CREATE TABLE `seg_type_confinement` (
  `confinetype_id` smallint(5) unsigned NOT NULL,
  `confinetypedesc` varchar(40) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`confinetype_id`)
);
