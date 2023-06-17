
DROP TABLE IF EXISTS `seg_dosages`;
CREATE TABLE `seg_dosages` (
  `id` char(36) NOT NULL,
  `dosage` tinytext NOT NULL,
  `times_a_day` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `create_id` varchar(25) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(25) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
);
