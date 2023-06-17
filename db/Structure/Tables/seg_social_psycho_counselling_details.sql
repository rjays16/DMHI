
DROP TABLE IF EXISTS `seg_social_psycho_counselling_details`;
CREATE TABLE `seg_social_psycho_counselling_details` (
  `id` int(11) NOT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `psycho_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
