
DROP TABLE IF EXISTS `seg_blood_type`;
CREATE TABLE `seg_blood_type` (
  `id` varchar(5) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `long_name` varchar(50) DEFAULT NULL,
  `group` varchar(5) DEFAULT NULL,
  `ordering` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
