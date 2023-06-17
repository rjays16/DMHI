
DROP TABLE IF EXISTS `seg_ops_rvu_temp`;
CREATE TABLE `seg_ops_rvu_temp` (
  `code` varchar(12) NOT NULL,
  `rvu` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`code`)
);
