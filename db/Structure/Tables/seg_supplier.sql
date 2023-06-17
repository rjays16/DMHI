
DROP TABLE IF EXISTS `seg_supplier`;
CREATE TABLE `seg_supplier` (
  `supplier_id` int(8) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `phone` varchar(35) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`supplier_id`),
  KEY `name` (`name`)
);
