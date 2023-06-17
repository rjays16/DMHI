
DROP TABLE IF EXISTS `seg_cmap_saro`;
CREATE TABLE `seg_cmap_saro` (
  `id` char(36) NOT NULL,
  `saro_no` varchar(15) NOT NULL,
  `saro_date` date NOT NULL,
  `dept_code` varchar(10) NOT NULL,
  `agency_code` varchar(10) NOT NULL,
  `fund_code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`saro_no`)
);
