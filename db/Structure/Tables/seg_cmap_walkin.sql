
DROP TABLE IF EXISTS `seg_cmap_walkin`;
CREATE TABLE `seg_cmap_walkin` (
  `id` varchar(36) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text NOT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `history` text,
  `create_time` datetime DEFAULT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  `modify_id` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
