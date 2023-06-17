
DROP TABLE IF EXISTS `seg_industrial_bill_h`;
CREATE TABLE `seg_industrial_bill_h` (
  `bill_nr` varchar(12) NOT NULL,
  `bill_rundate` datetime NOT NULL,
  `cutoff_date` date NOT NULL,
  `company_id` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `request_flag` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`bill_nr`)
);
