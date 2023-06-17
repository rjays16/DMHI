
DROP TABLE IF EXISTS `seg_dashlets`;
CREATE TABLE `seg_dashlets` (
  `id` char(36) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `preferences` text NOT NULL,
  `dashboard` char(36) NOT NULL,
  `mode` varchar(25) NOT NULL DEFAULT 'view',
  `state` varchar(25) NOT NULL DEFAULT 'normal',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `column_no` tinyint(3) unsigned NOT NULL,
  `rank` smallint(5) unsigned NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`dashboard`),
  KEY `FK_seg_dashlets_dashboard` (`dashboard`),
  KEY `FK_seg_dashlets_classes` (`class_name`),
  CONSTRAINT `FK_seg_dashlets_classes` FOREIGN KEY (`class_name`) REFERENCES `seg_dashlet_classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_dashlets_dashboard` FOREIGN KEY (`dashboard`) REFERENCES `seg_dashboards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
