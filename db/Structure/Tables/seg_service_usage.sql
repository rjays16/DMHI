
DROP TABLE IF EXISTS `seg_service_usage`;
CREATE TABLE `seg_service_usage` (
  `refno` varchar(12) NOT NULL,
  `served_date` date DEFAULT NULL,
  `ref_source` enum('LD','RD','BB') NOT NULL,
  `create_id` varchar(300) DEFAULT NULL,
  `create_tm` datetime DEFAULT NULL,
  `modify_id` varchar(300) DEFAULT NULL,
  `modify_tm` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`ref_source`)
);
