
DROP TABLE IF EXISTS `seg_promissory_blood_items`;
CREATE TABLE `seg_promissory_blood_items` (
  `item_id` int(10) unsigned NOT NULL,
  `lab_serv_refno` varchar(12) NOT NULL,
  `date_borrowed` datetime DEFAULT NULL,
  `no_of_units` double DEFAULT NULL,
  `serial_number` varchar(15) DEFAULT NULL,
  `date_replaced` datetime DEFAULT NULL,
  `no_of_units_replaced` double DEFAULT NULL,
  `item_status` varchar(12) DEFAULT NULL,
  `remarks` text,
  PRIMARY KEY (`item_id`),
  KEY `FK_seg_promissory_blood_items` (`lab_serv_refno`),
  CONSTRAINT `FK_seg_promissory_blood_items` FOREIGN KEY (`lab_serv_refno`) REFERENCES `seg_promissory_blood` (`lab_serv_refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
