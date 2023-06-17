
DROP TABLE IF EXISTS `seg_lingap_walkin_pharmacy`;
CREATE TABLE `seg_lingap_walkin_pharmacy` (
  `entry_id` char(36) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` tinyint(4) NOT NULL DEFAULT '1',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`entry_id`,`ref_no`,`service_code`),
  KEY `FK_seg_lingap_walkin_entry_details_order` (`ref_no`,`service_code`)
);
