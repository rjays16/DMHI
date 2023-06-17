
DROP TABLE IF EXISTS `seg_social_source_referral`;
CREATE TABLE `seg_social_source_referral` (
  `source_nr` varchar(10) NOT NULL,
  `source` varchar(50) NOT NULL,
  PRIMARY KEY (`source_nr`)
);
