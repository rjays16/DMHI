
DROP TABLE IF EXISTS `seg_product_classification`;
CREATE TABLE `seg_product_classification` (
  `class_code` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `lockflag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`class_code`)
);
