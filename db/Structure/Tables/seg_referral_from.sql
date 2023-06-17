
DROP TABLE IF EXISTS `seg_referral_from`;
CREATE TABLE `seg_referral_from` (
  `id` int(11) NOT NULL,
  `referral` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
