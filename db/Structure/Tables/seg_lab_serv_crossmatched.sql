
DROP TABLE IF EXISTS `seg_lab_serv_crossmatched`;
CREATE TABLE `seg_lab_serv_crossmatched` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `item_id` varchar(12) NOT NULL,
  `quantity` double DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`item_id`)
);
