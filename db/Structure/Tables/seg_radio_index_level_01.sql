
DROP TABLE IF EXISTS `seg_radio_index_level_01`;
CREATE TABLE `seg_radio_index_level_01` (
  `id` varchar(10) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  `order_no` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
);
