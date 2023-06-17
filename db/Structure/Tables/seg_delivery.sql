
DROP TABLE IF EXISTS `seg_delivery`;
CREATE TABLE `seg_delivery` (
  `refno` varchar(12) NOT NULL,
  `receipt_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `receiving_id` int(11) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `supplier_id` int(8) NOT NULL,
  `remarks` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(32) NOT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(32) NOT NULL DEFAULT '',
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_delivery_personell` (`receiving_id`),
  CONSTRAINT `FK_seg_delivery_personell` FOREIGN KEY (`receiving_id`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
