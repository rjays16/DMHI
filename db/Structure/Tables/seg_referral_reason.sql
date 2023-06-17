
DROP TABLE IF EXISTS `seg_referral_reason`;
CREATE TABLE `seg_referral_reason` (
  `id` int(11) NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
