
DROP TABLE IF EXISTS `seg_lingap_entries_laboratory`;
CREATE TABLE `seg_lingap_entries_laboratory` (
  `entry_id` char(36) NOT NULL,
  `ref_no` varchar(10) NOT NULL,
  `service_code` varchar(15) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` decimal(18,4) NOT NULL DEFAULT '1.0000',
  `amount` decimal(18,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`entry_id`,`ref_no`,`service_code`),
  KEY `FK_seg_lingap_entries_laboratory_service` (`service_code`),
  KEY `FK_seg_lingap_entries_laboratory_serv` (`ref_no`,`service_code`),
  CONSTRAINT `FK_seg_lingap_entries_laboratory` FOREIGN KEY (`entry_id`) REFERENCES `seg_lingap_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_lingap_entries_laboratory_serv` FOREIGN KEY (`ref_no`, `service_code`) REFERENCES `seg_lab_servdetails` (`refno`, `service_code`) ON UPDATE CASCADE
);
