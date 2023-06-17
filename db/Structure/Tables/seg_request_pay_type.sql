
DROP TABLE IF EXISTS `seg_request_pay_type`;
CREATE TABLE `seg_request_pay_type` (
  `refno` varchar(25) NOT NULL,
  `service_code` varchar(25) DEFAULT NULL,
  `pay_type` varchar(25) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `history` text,
  `created_id` varchar(25) DEFAULT NULL,
  `created_tm` datetime DEFAULT NULL,
  `modified_id` varchar(25) DEFAULT NULL,
  `modified_tm` datetime DEFAULT NULL,
  `encounter_nr` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
