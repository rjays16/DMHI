
DROP TABLE IF EXISTS `or_main_other_requests`;
CREATE TABLE `or_main_other_requests` (
  `request_id` bigint(20) unsigned NOT NULL,
  `request_refno` varchar(12) DEFAULT NULL,
  `or_refno` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `location` tinyint(4) DEFAULT NULL,
  `insert_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`request_id`),
  KEY `FK_or_main_other_requests` (`or_refno`),
  CONSTRAINT `FK_or_main_other_requests` FOREIGN KEY (`or_refno`) REFERENCES `seg_ops_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
