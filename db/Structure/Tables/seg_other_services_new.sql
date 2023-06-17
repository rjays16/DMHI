
DROP TABLE IF EXISTS `seg_other_services_new`;
CREATE TABLE `seg_other_services_new` (
  `service_code` varchar(12) NOT NULL,
  `alt_service_code` varchar(12) NOT NULL COMMENT 'Alternate service code to use for billing module.',
  `name` varchar(150) NOT NULL,
  `name_short` varchar(15) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `account_type` int(11) DEFAULT NULL,
  `is_billing_related` tinyint(1) NOT NULL DEFAULT '0',
  `lockflag` tinyint(1) NOT NULL DEFAULT '0',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`service_code`),
  UNIQUE KEY `alt_service_code` (`alt_service_code`),
  KEY `FK_seg_other_services_account_type` (`account_type`)
);