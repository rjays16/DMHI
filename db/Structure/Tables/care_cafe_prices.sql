
DROP TABLE IF EXISTS `care_cafe_prices`;
CREATE TABLE `care_cafe_prices` (
  `item` int(11) NOT NULL,
  `lang` varchar(10) NOT NULL DEFAULT 'en',
  `productgroup` tinytext NOT NULL,
  `article` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `price` varchar(10) NOT NULL DEFAULT '',
  `unit` tinytext NOT NULL,
  `pic_filename` tinytext NOT NULL,
  `modify_id` varchar(25) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(25) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `item` (`item`),
  KEY `lang` (`lang`)
);
