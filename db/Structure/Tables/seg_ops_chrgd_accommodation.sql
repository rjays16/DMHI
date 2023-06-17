
DROP TABLE IF EXISTS `seg_ops_chrgd_accommodation`;
CREATE TABLE `seg_ops_chrgd_accommodation` (
  `refno` varchar(12) NOT NULL,
  `entry_no` smallint(5) unsigned NOT NULL,
  `ops_refno` varchar(12) NOT NULL,
  `ops_entryno` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ops_code` varchar(12) NOT NULL,
  `rvu` int(10) unsigned NOT NULL,
  `multiplier` decimal(10,4) NOT NULL,
  PRIMARY KEY (`refno`,`entry_no`,`ops_refno`,`ops_entryno`,`ops_code`)
);
