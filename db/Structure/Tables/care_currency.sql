
DROP TABLE IF EXISTS `care_currency`;
CREATE TABLE `care_currency` (
  `item_no` smallint(5) unsigned NOT NULL,
  `short_name` varchar(5) NOT NULL DEFAULT '',
  `long_name` varchar(20) NOT NULL DEFAULT '',
  `info` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(5) NOT NULL DEFAULT '',
  `modify_id` varchar(20) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(20) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `item_no` (`item_no`),
  KEY `short_name` (`short_name`)
);
