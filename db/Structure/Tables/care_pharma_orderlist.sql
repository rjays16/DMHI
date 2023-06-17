
DROP TABLE IF EXISTS `care_pharma_orderlist`;
CREATE TABLE `care_pharma_orderlist` (
  `order_nr` int(11) NOT NULL,
  `dept_nr` int(3) NOT NULL DEFAULT '0',
  `order_date` date NOT NULL DEFAULT '0000-00-00',
  `order_time` time NOT NULL DEFAULT '00:00:00',
  `articles` text NOT NULL,
  `extra1` tinytext NOT NULL,
  `extra2` text NOT NULL,
  `validator` tinytext NOT NULL,
  `ip_addr` tinytext NOT NULL,
  `priority` tinytext NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sent_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `process_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_nr`,`dept_nr`),
  KEY `dept` (`dept_nr`)
);
