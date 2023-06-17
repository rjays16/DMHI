
DROP TABLE IF EXISTS `seg_rep_hospital_income_tbl`;
CREATE TABLE `seg_rep_hospital_income_tbl` (
  `refno` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `group_code` varchar(10) DEFAULT NULL,
  `encounter_type` smallint(5) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `service_code` varchar(10) DEFAULT NULL,
  `fromBB` tinyint(1) DEFAULT NULL,
  `is_rdu` tinyint(1) DEFAULT NULL,
  `DATE_` date DEFAULT NULL,
  `SHIFT` time DEFAULT NULL,
  `AMT_PAID` decimal(10,2) DEFAULT NULL,
  `Charity` decimal(10,2) DEFAULT NULL,
  `PHS` decimal(10,2) DEFAULT NULL,
  `LINGAP` decimal(10,2) DEFAULT NULL,
  `CMAP` decimal(10,2) DEFAULT NULL,
  `PHIC` decimal(10,2) DEFAULT NULL,
  `SOCIALIZED` decimal(10,2) DEFAULT NULL,
  `CHARGE` decimal(10,2) DEFAULT NULL,
  `RDU` decimal(10,2) DEFAULT NULL,
  `OTHER` decimal(10,2) DEFAULT NULL,
  `LAB` decimal(10,2) DEFAULT NULL,
  `BLOOD` decimal(10,2) DEFAULT NULL
);
