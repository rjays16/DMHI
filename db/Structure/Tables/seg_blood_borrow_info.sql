
DROP TABLE IF EXISTS `seg_blood_borrow_info`;
CREATE TABLE `seg_blood_borrow_info` (
  `refno` varchar(12) NOT NULL,
  `is_borrowed` tinyint(1) DEFAULT '0',
  `qty_borrowed` double DEFAULT NULL,
  `bb_remarks` text,
  `partner_type` varchar(12) DEFAULT NULL,
  `partner_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`refno`),
  KEY `FK_seg_blood_borrow_info_type` (`partner_type`),
  CONSTRAINT `FK_seg_blood_borrow_info` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
