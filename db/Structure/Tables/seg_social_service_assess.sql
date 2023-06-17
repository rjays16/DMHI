
DROP TABLE IF EXISTS `seg_social_service_assess`;
CREATE TABLE `seg_social_service_assess` (
  `id` int(10) NOT NULL,
  `desc` varchar(500) DEFAULT NULL,
  `group` varchar(10) NOT NULL,
  PRIMARY KEY (`id`,`group`),
  KEY `FK_seg_social_service_assess` (`group`),
  CONSTRAINT `FK_seg_social_service_assess` FOREIGN KEY (`group`) REFERENCES `seg_social_service_assess_group` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
