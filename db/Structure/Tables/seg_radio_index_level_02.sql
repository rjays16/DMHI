
DROP TABLE IF EXISTS `seg_radio_index_level_02`;
CREATE TABLE `seg_radio_index_level_02` (
  `id` varchar(10) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `id_level_01` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_radio_index_level_02` (`id_level_01`),
  KEY `name` (`name`),
  CONSTRAINT `FK_seg_radio_index_level_02` FOREIGN KEY (`id_level_01`) REFERENCES `seg_radio_index_level_01` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
