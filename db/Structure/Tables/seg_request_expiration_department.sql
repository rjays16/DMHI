
DROP TABLE IF EXISTS `seg_request_expiration_department`;
CREATE TABLE `seg_request_expiration_department` (
  `id` int(10) unsigned NOT NULL,
  `module` enum('radiology') DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `value` tinyint(4) DEFAULT NULL,
  `unit` enum('DAY','WEEK','MONTH','YEAR') DEFAULT NULL,
  PRIMARY KEY (`id`)
);
