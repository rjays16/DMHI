
DROP TABLE IF EXISTS `seg_caserate_procedure2`;
CREATE TABLE `seg_caserate_procedure2` (
  `rvs_code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `package` double NOT NULL,
  `hf` double NOT NULL,
  `pf` double NOT NULL
);
