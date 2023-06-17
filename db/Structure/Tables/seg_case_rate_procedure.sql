
DROP TABLE IF EXISTS `seg_case_rate_procedure`;
CREATE TABLE `seg_case_rate_procedure` (
  `code` varchar(15) NOT NULL,
  `package` double(20,2) NOT NULL,
  `hf` double(20,2) NOT NULL,
  `pf` double(20,2) NOT NULL
);
