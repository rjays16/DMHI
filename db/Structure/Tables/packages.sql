
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `package_id` smallint(5) unsigned NOT NULL,
  `package_name` varchar(100) DEFAULT NULL,
  `package_price` double DEFAULT NULL,
  `clinic_id` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`package_id`),
  UNIQUE KEY `package_id` (`package_id`)
);
