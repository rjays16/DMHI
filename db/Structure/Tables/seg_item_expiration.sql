
DROP TABLE IF EXISTS `seg_item_expiration`;
CREATE TABLE `seg_item_expiration` (
  `id` int(10) unsigned NOT NULL,
  `module` enum('radiology') DEFAULT NULL,
  `service_code` varchar(11) NOT NULL,
  `value` tinyint(4) DEFAULT NULL,
  `unit` enum('DAY','WEEK','MONTH','YEAR') DEFAULT NULL,
  PRIMARY KEY (`id`)
);
