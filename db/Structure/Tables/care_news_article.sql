
DROP TABLE IF EXISTS `care_news_article`;
CREATE TABLE `care_news_article` (
  `nr` int(11) NOT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'en',
  `dept_nr` smallint(5) unsigned NOT NULL DEFAULT '0',
  `category` tinytext NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `title` varchar(255) NOT NULL DEFAULT '',
  `preface` text NOT NULL,
  `body` text NOT NULL,
  `pic` blob,
  `pic_mime` varchar(4) DEFAULT NULL,
  `art_num` tinyint(1) NOT NULL DEFAULT '0',
  `head_file` tinytext NOT NULL,
  `main_file` tinytext NOT NULL,
  `pic_file` tinytext NOT NULL,
  `author` varchar(30) NOT NULL DEFAULT '',
  `submit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `encode_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_date` date NOT NULL DEFAULT '0000-00-00',
  `modify_id` varchar(30) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(30) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`nr`),
  KEY `item_no` (`nr`)
);
