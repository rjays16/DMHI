
DROP TABLE IF EXISTS `seg_or_sponge`;
CREATE TABLE `seg_or_sponge` (
  `or_main_refno` bigint(20) unsigned NOT NULL,
  `sponge_code` varchar(25) NOT NULL,
  `f_count_table` int(10) unsigned DEFAULT '0',
  `f_count_floor` int(10) unsigned DEFAULT '0',
  `s_count_table` int(10) unsigned DEFAULT '0',
  `s_count_floor` int(10) unsigned DEFAULT '0',
  `initial_count` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`or_main_refno`,`sponge_code`),
  KEY `FK_seg_or_sponge2` (`sponge_code`),
  CONSTRAINT `FK_seg_or_sponge2` FOREIGN KEY (`sponge_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE
);
