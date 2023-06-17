
DROP TABLE IF EXISTS `seg_radio_service_sized`;
CREATE TABLE `seg_radio_service_sized` (
  `batch_nr` int(10) NOT NULL,
  `id_size` int(11) NOT NULL,
  `item_id` varchar(25) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `no_film_used` int(5) DEFAULT NULL,
  `no_film_spoilage` int(5) DEFAULT '0',
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`batch_nr`,`id_size`,`item_id`),
  KEY `FK_seg_radio_service_sized` (`id_size`),
  KEY `FK_seg_radio_service_sized_item` (`item_id`),
  CONSTRAINT `FK_seg_radio_service_sized` FOREIGN KEY (`id_size`) REFERENCES `seg_radio_film_size` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
