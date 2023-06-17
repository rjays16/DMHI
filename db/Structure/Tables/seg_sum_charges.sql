
DROP TABLE IF EXISTS `seg_sum_charges`;
CREATE TABLE `seg_sum_charges` (
  `date_index` date NOT NULL DEFAULT '0000-00-00',
  `entry_no` smallint(5) unsigned NOT NULL DEFAULT '0',
  `charge_desc` varchar(150) DEFAULT NULL,
  `actual` double(12,2) DEFAULT NULL,
  `phic` double(12,2) DEFAULT NULL,
  `discount` double(12,2) DEFAULT NULL,
  PRIMARY KEY (`date_index`,`entry_no`)
);
