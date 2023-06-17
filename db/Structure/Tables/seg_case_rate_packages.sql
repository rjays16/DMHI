
DROP TABLE IF EXISTS `seg_case_rate_packages`;
CREATE TABLE `seg_case_rate_packages` (
  `package_id` int(11) NOT NULL,
  `code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `group` text,
  `package` double(15,2) NOT NULL,
  `hf` double(15,2) NOT NULL,
  `pf` double(15,2) NOT NULL,
  `shf` double(15,2) NOT NULL,
  `spf` double(15,2) NOT NULL,
  `case_type` enum('m','p') NOT NULL,
  `special_case` tinyint(1) DEFAULT '0',
  `for_infirmaries` tinyint(1) DEFAULT '0',
  `for_laterality` tinyint(1) DEFAULT '0',
  `is_allowed_second` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`package_id`),
  KEY `idx_caserate_code` (`code`)
);
