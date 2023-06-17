
DROP TABLE IF EXISTS `seg_inventory_adjustment`;
CREATE TABLE `seg_inventory_adjustment` (
  `refno` varchar(12) NOT NULL,
  `adjust_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `adjusting_id` int(11) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `remarks` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_inventory_adjustment_personell` (`adjusting_id`)
);
