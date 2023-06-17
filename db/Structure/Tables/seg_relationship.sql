
DROP TABLE IF EXISTS `seg_relationship`;
CREATE TABLE `seg_relationship` (
  `id` int(11) NOT NULL,
  `child_relation` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);
