
DROP TABLE IF EXISTS `care_diagnosis_localcode`;
CREATE TABLE `care_diagnosis_localcode` (
  `localcode` varchar(12) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `class_sub` varchar(5) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL DEFAULT '',
  `inclusive` text NOT NULL,
  `exclusive` text NOT NULL,
  `notes` text NOT NULL,
  `std_code` char(1) NOT NULL DEFAULT '',
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` text NOT NULL,
  `extra_codes` text NOT NULL,
  `extra_subclass` text NOT NULL,
  `search_keys` varchar(255) NOT NULL DEFAULT '',
  `use_frequency` int(11) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`localcode`),
  KEY `diagnosis_code` (`localcode`)
);
