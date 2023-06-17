
DROP TABLE IF EXISTS `care_med_products_main`;
CREATE TABLE `care_med_products_main` (
  `bestellnum` varchar(25) NOT NULL DEFAULT '',
  `artikelnum` tinytext NOT NULL,
  `industrynum` tinytext NOT NULL,
  `artikelname` tinytext NOT NULL,
  `generic` tinytext NOT NULL,
  `description` text NOT NULL,
  `packing` tinytext NOT NULL,
  `minorder` int(4) NOT NULL DEFAULT '0',
  `maxorder` int(4) NOT NULL DEFAULT '0',
  `proorder` tinytext NOT NULL,
  `picfile` tinytext NOT NULL,
  `encoder` tinytext NOT NULL,
  `enc_date` tinytext NOT NULL,
  `enc_time` tinytext NOT NULL,
  `lock_flag` tinyint(1) NOT NULL DEFAULT '0',
  `medgroup` text NOT NULL,
  `cave` tinytext NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '',
  `history` text NOT NULL,
  `modify_id` varchar(35) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_id` varchar(35) NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bestellnum`),
  KEY `bestellnum` (`bestellnum`)
);
