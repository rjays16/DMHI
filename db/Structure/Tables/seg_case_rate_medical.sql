
DROP TABLE IF EXISTS `seg_case_rate_medical`;
CREATE TABLE `seg_case_rate_medical` (
  `code` varchar(15) NOT NULL,
  `package` double(15,2) NOT NULL,
  `hf` double(15,2) NOT NULL,
  `pf` double(15,2) NOT NULL
);
