
DROP TABLE IF EXISTS `seg_lingap_entry_details`;
CREATE TABLE `seg_lingap_entry_details` (
  `entry_id` char(36) NOT NULL,
  `ref_source` enum('PH','RD','LD','OR','MISC') NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `service_code` varchar(15) NOT NULL,
  `entry_no` varchar(5) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `quantity` tinyint(4) NOT NULL DEFAULT '1',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`entry_id`,`ref_source`,`ref_no`,`service_code`,`entry_no`)
);
