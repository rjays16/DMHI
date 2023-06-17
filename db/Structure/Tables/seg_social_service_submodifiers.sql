
DROP TABLE IF EXISTS `seg_social_service_submodifiers`;
CREATE TABLE `seg_social_service_submodifiers` (
  `mod_code` int(5) NOT NULL,
  `mod_subcode` float NOT NULL,
  `mod_subdesc` text,
  `is_deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`mod_code`,`mod_subcode`)
);
