
DROP TABLE IF EXISTS `seg_blood_received_status`;
CREATE TABLE `seg_blood_received_status` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `ordering` int(3) NOT NULL,
  `done_date` datetime DEFAULT NULL,
  `issuance_date` datetime DEFAULT NULL,
  `history` text,
  `create_id` varchar(60) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`ordering`)
);
