
DROP TABLE IF EXISTS `mmhr_table`;
CREATE TABLE `mmhr_table` (
  `unique_id` varchar(40) NOT NULL,
  `ref_date` date NOT NULL,
  `nhip` int(11) DEFAULT '0',
  `non_nhip` int(11) DEFAULT '0',
  PRIMARY KEY (`unique_id`,`ref_date`)
);
