
DROP TABLE IF EXISTS `care_steri_products_main`;
CREATE TABLE `care_steri_products_main` (
  `bestellnum` int(15) unsigned NOT NULL DEFAULT '0',
  `containernum` varchar(15) NOT NULL DEFAULT '',
  `industrynum` tinytext NOT NULL,
  `containername` varchar(40) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `packing` tinytext NOT NULL,
  `minorder` int(4) unsigned NOT NULL DEFAULT '0',
  `maxorder` int(4) unsigned NOT NULL DEFAULT '0',
  `proorder` tinytext NOT NULL,
  `picfile` tinytext NOT NULL,
  `encoder` tinytext NOT NULL,
  `enc_date` tinytext NOT NULL,
  `enc_time` tinytext NOT NULL,
  `lock_flag` tinyint(1) NOT NULL DEFAULT '0',
  `medgroup` text NOT NULL,
  `cave` tinytext NOT NULL
);
