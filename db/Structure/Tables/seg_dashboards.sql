
DROP TABLE IF EXISTS `seg_dashboards`;
CREATE TABLE `seg_dashboards` (
  `id` char(36) NOT NULL,
  `title` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `owner` varchar(35) NOT NULL,
  `columns` tinyint(1) NOT NULL DEFAULT '1',
  `column_widths` tinytext NOT NULL,
  `position` smallint(6) unsigned NOT NULL DEFAULT '0',
  `is_deleted` tinyint(4) NOT NULL,
  `history` text NOT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
