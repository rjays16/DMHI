
DROP TABLE IF EXISTS `care_pharma_ordercatalog`;
CREATE TABLE `care_pharma_ordercatalog` (
  `item_no` int(11) NOT NULL,
  `dept_nr` int(3) NOT NULL DEFAULT '0',
  `hit` int(11) NOT NULL DEFAULT '0',
  `artikelname` tinytext NOT NULL,
  `bestellnum` varchar(20) NOT NULL DEFAULT '',
  `minorder` int(4) NOT NULL DEFAULT '0',
  `maxorder` int(4) NOT NULL DEFAULT '0',
  `proorder` tinytext NOT NULL,
  KEY `item_no` (`item_no`)
);
