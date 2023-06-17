
DROP TABLE IF EXISTS `seg_blood_products_services`;
CREATE TABLE `seg_blood_products_services` (
  `service_code` varchar(10) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  PRIMARY KEY (`service_code`,`item_code`),
  KEY `FK_seg_blood_products_item` (`item_code`)
);
