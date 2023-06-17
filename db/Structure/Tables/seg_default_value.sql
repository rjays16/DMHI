
DROP TABLE IF EXISTS `seg_default_value`;
CREATE TABLE `seg_default_value` (
  `id` int(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `source` enum('SS','LD','RD','PH') DEFAULT NULL,
  `value` float DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
