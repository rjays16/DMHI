
DROP TABLE IF EXISTS `seg_cmap_entries_added`;
CREATE TABLE `seg_cmap_entries_added` (
  `id` int(100) NOT NULL,
  `pid` varchar(12) DEFAULT '0',
  `walkin_pid` int(7) DEFAULT NULL,
  `referral_id` int(10) DEFAULT NULL,
  `service_code` varchar(10) DEFAULT NULL,
  `service_name` varchar(50) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `amount` int(100) DEFAULT '0',
  `remarks` varchar(100) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  `history` text NOT NULL,
  KEY `id` (`id`)
);
