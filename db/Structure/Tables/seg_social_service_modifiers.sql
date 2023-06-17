
DROP TABLE IF EXISTS `seg_social_service_modifiers`;
CREATE TABLE `seg_social_service_modifiers` (
  `mod_code` int(5) NOT NULL,
  `mod_desc` text,
  `mod_short` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`mod_code`)
);
