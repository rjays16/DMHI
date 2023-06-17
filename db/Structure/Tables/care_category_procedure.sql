
DROP TABLE IF EXISTS `care_category_procedure`;
CREATE TABLE `care_category_procedure` (
  `nr` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category` varchar(35) NOT NULL DEFAULT '',
  `name` varchar(35) NOT NULL DEFAULT '',
  `LD_var` varchar(35) NOT NULL DEFAULT '',
  `short_code` char(1) NOT NULL DEFAULT '',
  `LD_var_short_code` varchar(25) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `hide_from` varchar(255) NOT NULL DEFAULT '0',
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`)
);
