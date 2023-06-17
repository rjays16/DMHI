
DROP TABLE IF EXISTS `seg_industrial_purpose`;
CREATE TABLE `seg_industrial_purpose` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
