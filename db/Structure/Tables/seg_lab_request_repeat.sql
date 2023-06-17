
DROP TABLE IF EXISTS `seg_lab_request_repeat`;
CREATE TABLE `seg_lab_request_repeat` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `index_series` smallint(2) NOT NULL,
  `served_date` datetime DEFAULT NULL,
  `no_repeat` smallint(2) DEFAULT NULL,
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`service_code`,`index_series`)
);
