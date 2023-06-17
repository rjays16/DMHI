
DROP TABLE IF EXISTS `seg_medrec_notifications`;
CREATE TABLE `seg_medrec_notifications` (
  `id` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `term` char(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
