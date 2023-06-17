
DROP TABLE IF EXISTS `seg_caserate_procedure`;
CREATE TABLE `seg_caserate_procedure` (
  `rvs_code` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `package` double NOT NULL,
  `hf` double NOT NULL,
  `pf` double NOT NULL,
  KEY `rvs_code` (`rvs_code`)
);
