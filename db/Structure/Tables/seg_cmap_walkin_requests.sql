
DROP TABLE IF EXISTS `seg_cmap_walkin_requests`;
CREATE TABLE `seg_cmap_walkin_requests` (
  `id` varchar(36) NOT NULL,
  `walkin_id` varchar(36) NOT NULL,
  `request_source` smallint(6) NOT NULL,
  `item` varchar(250) NOT NULL,
  `price` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`)
);
