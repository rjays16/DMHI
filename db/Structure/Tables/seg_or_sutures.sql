
DROP TABLE IF EXISTS `seg_or_sutures`;
CREATE TABLE `seg_or_sutures` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `history` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(40) DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
