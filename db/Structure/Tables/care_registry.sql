
DROP TABLE IF EXISTS `care_registry`;
CREATE TABLE `care_registry` (
  `registry_id` varchar(35) NOT NULL DEFAULT '',
  `module_start_script` varchar(60) NOT NULL DEFAULT '',
  `news_start_script` varchar(60) NOT NULL DEFAULT '',
  `news_editor_script` varchar(255) NOT NULL DEFAULT '',
  `news_reader_script` varchar(255) NOT NULL DEFAULT '',
  `passcheck_script` varchar(255) NOT NULL DEFAULT '',
  `composite` text NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`registry_id`)
);
