
DROP TABLE IF EXISTS `seg_caserate_infirmary`;
CREATE TABLE `seg_caserate_infirmary` (
  `ICD CODE` varchar(15) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `Case Rate` double(10,4) NOT NULL,
  `Professional` double(10,4) NOT NULL,
  `Health Care` double(10,4) NOT NULL
);
