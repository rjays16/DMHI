
DROP TABLE IF EXISTS `seg_rep_ss_data_tbl`;
CREATE TABLE `seg_rep_ss_data_tbl` (
  `grant_date` datetime DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `encounter_type` smallint(5) DEFAULT NULL,
  `discountid` varchar(10) DEFAULT NULL,
  `opd_class` tinyint(1) DEFAULT '1'
);
