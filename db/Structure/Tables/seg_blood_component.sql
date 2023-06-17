
DROP TABLE IF EXISTS `seg_blood_component`;
CREATE TABLE `seg_blood_component` (
  `id` varchar(12) NOT NULL,
  `name` varchar(65) DEFAULT NULL,
  `long_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
