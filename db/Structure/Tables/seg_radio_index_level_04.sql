
DROP TABLE IF EXISTS `seg_radio_index_level_04`;
CREATE TABLE `seg_radio_index_level_04` (
  `id` varchar(10) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `order_no` int(11) DEFAULT NULL,
  `id_level_03` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_radio_index_level_04` (`id_level_03`),
  KEY `name` (`name`),
  CONSTRAINT `FK_seg_radio_index_level_04` FOREIGN KEY (`id_level_03`) REFERENCES `seg_radio_index_level_03` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
