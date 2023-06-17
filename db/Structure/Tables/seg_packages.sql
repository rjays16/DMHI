
DROP TABLE IF EXISTS `seg_packages`;
CREATE TABLE `seg_packages` (
  `package_id` smallint(5) unsigned NOT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `package_price` double DEFAULT NULL,
  `is_surgical` tinyint(4) NOT NULL DEFAULT '0',
  `pkg_phiccode` varchar(10) NOT NULL,
  `is_zpackage` tinyint(1) NOT NULL DEFAULT '0',
  `create_id` varchar(60) DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `modify_time` timestamp NULL DEFAULT NULL,
  `history` text,
  `clinic_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`package_id`),
  UNIQUE KEY `package_id` (`package_id`)
);
