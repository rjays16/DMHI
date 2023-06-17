
DROP TABLE IF EXISTS `seg_blood_received_header`;
CREATE TABLE `seg_blood_received_header` (
  `refno` varchar(12) NOT NULL,
  `status` enum('complete','lack','none') DEFAULT NULL,
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
