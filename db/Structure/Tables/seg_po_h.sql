
DROP TABLE IF EXISTS `seg_po_h`;
CREATE TABLE `seg_po_h` (
  `po_no` varchar(12) NOT NULL,
  `po_date` date DEFAULT NULL,
  `create_tm` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `modify_tm` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`po_no`)
);
