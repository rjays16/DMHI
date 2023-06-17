
DROP TABLE IF EXISTS `seg_or_main_status`;
CREATE TABLE `seg_or_main_status` (
  `status_req_id` bigint(20) unsigned NOT NULL,
  `or_main_refno` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `reason` text,
  `history` text,
  `created_id` varchar(35) DEFAULT NULL,
  `modified_id` varchar(35) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`status_req_id`),
  KEY `FK_seg_or_main_status` (`or_main_refno`),
  CONSTRAINT `FK_seg_or_main_status` FOREIGN KEY (`or_main_refno`) REFERENCES `seg_or_main` (`or_main_refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
