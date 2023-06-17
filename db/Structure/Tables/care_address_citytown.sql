
DROP TABLE IF EXISTS `care_address_citytown`;
CREATE TABLE `care_address_citytown` (
  `nr` mediumint(8) unsigned NOT NULL,
  `unece_modifier` char(2) DEFAULT NULL,
  `unece_locode` varchar(15) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(25) DEFAULT NULL,
  `iso_country_id` char(3) NOT NULL DEFAULT '',
  `unece_locode_type` tinyint(3) unsigned DEFAULT NULL,
  `unece_coordinates` varchar(25) DEFAULT NULL,
  `info_url` varchar(255) DEFAULT NULL,
  `use_frequency` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` varchar(25) DEFAULT NULL,
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `name` (`name`)
);
