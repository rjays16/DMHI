
DROP TABLE IF EXISTS `seg_social_concrete_referral_details`;
CREATE TABLE `seg_social_concrete_referral_details` (
  `id` int(11) NOT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `concrete_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
