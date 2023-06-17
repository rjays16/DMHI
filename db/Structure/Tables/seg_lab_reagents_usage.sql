
DROP TABLE IF EXISTS `seg_lab_reagents_usage`;
CREATE TABLE `seg_lab_reagents_usage` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `item_qty` double NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  `is_unitperpc` tinyint(1) NOT NULL,
  PRIMARY KEY (`refno`,`service_code`,`item_code`)
);
