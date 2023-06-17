
DROP TABLE IF EXISTS `seg_social_service_assess_details`;
CREATE TABLE `seg_social_service_assess_details` (
  `id` int(10) NOT NULL,
  `desc` varchar(500) DEFAULT NULL,
  `assess_id` int(10) NOT NULL,
  PRIMARY KEY (`id`,`assess_id`),
  KEY `FK_social_service_assess_details` (`assess_id`)
);
