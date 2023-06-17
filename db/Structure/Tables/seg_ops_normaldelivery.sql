
DROP TABLE IF EXISTS `seg_ops_normaldelivery`;
CREATE TABLE `seg_ops_normaldelivery` (
  `id` tinyint(4) NOT NULL,
  `ops_code` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_ops_normaldelivery_ops_code` (`ops_code`),
  CONSTRAINT `FK_seg_ops_normaldelivery_ops_code` FOREIGN KEY (`ops_code`) REFERENCES `seg_ops_rvs` (`code`) ON UPDATE CASCADE
);
