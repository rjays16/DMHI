
DROP TABLE IF EXISTS `seg_radio_index_level_03`;
CREATE TABLE `seg_radio_index_level_03` (
  `id` varchar(10) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `id_level_02` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_radio_index_level_03` (`id_level_02`),
  KEY `name` (`name`),
  CONSTRAINT `FK_seg_radio_index_level_03` FOREIGN KEY (`id_level_02`) REFERENCES `seg_radio_index_level_02` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
