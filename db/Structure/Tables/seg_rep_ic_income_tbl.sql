
DROP TABLE IF EXISTS `seg_rep_ic_income_tbl`;
CREATE TABLE `seg_rep_ic_income_tbl` (
  `refno` varchar(12) DEFAULT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_type` smallint(5) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `DATE_` date DEFAULT NULL,
  `SHIFT` time DEFAULT NULL,
  `LAB` decimal(10,2) DEFAULT NULL,
  `LAB_ORIG` decimal(10,2) DEFAULT NULL,
  `SPL` decimal(10,2) DEFAULT NULL,
  `SPL_ORIG` decimal(10,2) DEFAULT NULL,
  `ICLAB` decimal(10,2) DEFAULT NULL,
  `ICLAB_ORIG` decimal(10,2) DEFAULT NULL,
  `BLOOD` decimal(10,2) DEFAULT NULL,
  `BLOOD_ORIG` decimal(10,2) DEFAULT NULL,
  `RADIO` decimal(10,2) DEFAULT NULL,
  `RADIO_ORIG` decimal(10,2) DEFAULT NULL,
  `PHARMA_MG` decimal(10,2) DEFAULT NULL,
  `PHARMA_IP` decimal(10,2) DEFAULT NULL,
  `MISC` decimal(10,2) DEFAULT NULL
);
