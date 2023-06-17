
DROP TABLE IF EXISTS `seg_dialysis_package`;
CREATE TABLE `seg_dialysis_package` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(36) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `history` text,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
);
