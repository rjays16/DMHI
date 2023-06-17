
DROP TABLE IF EXISTS `care_cafe_menu`;
CREATE TABLE `care_cafe_menu` (
  `item` int(11) NOT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'en',
  `cdate` date NOT NULL DEFAULT '0000-00-00',
  `menu` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `item_2` (`item`),
  KEY `item` (`item`,`lang`),
  KEY `cdate` (`cdate`)
);
