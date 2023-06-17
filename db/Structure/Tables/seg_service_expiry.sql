
DROP TABLE IF EXISTS `seg_service_expiry`;
CREATE TABLE `seg_service_expiry` (
  `id` int(5) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `source` enum('SS','LD','RD','PH') DEFAULT NULL,
  `expiry_len_days` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
