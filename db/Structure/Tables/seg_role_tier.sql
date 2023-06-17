
DROP TABLE IF EXISTS `seg_role_tier`;
CREATE TABLE `seg_role_tier` (
  `tier_nr` int(10) unsigned NOT NULL,
  `tier_desc` varchar(80) NOT NULL,
  PRIMARY KEY (`tier_nr`)
);
