
DROP TABLE IF EXISTS `seg_item_rate`;
CREATE TABLE `seg_item_rate` (
  `nr` int(15) NOT NULL,
  `source` varchar(15) NOT NULL,
  `group_code` varchar(15) NOT NULL,
  `item_code` varchar(15) DEFAULT NULL,
  `room_type` int(5) NOT NULL,
  `cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`nr`)
);
