
DROP TABLE IF EXISTS `care_menu_main`;
CREATE TABLE `care_menu_main` (
  `nr` tinyint(3) unsigned NOT NULL,
  `sort_nr` tinyint(2) NOT NULL DEFAULT '0',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `is_visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `hide_by` text,
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
