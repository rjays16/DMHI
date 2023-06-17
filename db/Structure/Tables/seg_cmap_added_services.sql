
DROP TABLE IF EXISTS `seg_cmap_added_services`;
CREATE TABLE `seg_cmap_added_services` (
  `id` int(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  `history` text NOT NULL,
  KEY `id` (`id`)
);
