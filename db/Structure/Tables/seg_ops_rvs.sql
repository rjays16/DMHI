
DROP TABLE IF EXISTS `seg_ops_rvs`;
CREATE TABLE `seg_ops_rvs` (
  `code` varchar(12) NOT NULL,
  `description` text NOT NULL,
  `rvu` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `modify_id` varchar(35) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`code`)
);
