
DROP TABLE IF EXISTS `seg_ops_servdetails`;
CREATE TABLE `seg_ops_servdetails` (
  `refno` varchar(12) NOT NULL,
  `ops_code` varchar(12) NOT NULL,
  `rvu` int(10) unsigned NOT NULL,
  `multiplier` decimal(10,4) NOT NULL,
  `group_code` varchar(4) NOT NULL,
  PRIMARY KEY (`refno`,`ops_code`),
  KEY `FK_seg_ops_servdetails_ops` (`ops_code`),
  CONSTRAINT `FK_seg_ops_servdetails_ops_rvs` FOREIGN KEY (`ops_code`) REFERENCES `seg_ops_rvs` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_ops_servdetails_serv_h` FOREIGN KEY (`refno`) REFERENCES `seg_ops_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
