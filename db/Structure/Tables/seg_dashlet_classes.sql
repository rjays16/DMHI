
DROP TABLE IF EXISTS `seg_dashlet_classes`;
CREATE TABLE `seg_dashlet_classes` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `category` varchar(100) NOT NULL,
  `class_path` varchar(250) DEFAULT NULL,
  `class_file` varchar(100) DEFAULT NULL,
  `selectable` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `tags` tinytext,
  PRIMARY KEY (`id`)
);
