
DROP TABLE IF EXISTS `seg_total_byaccommodationtype`;
CREATE TABLE `seg_total_byaccommodationtype` (
  `date_index` date NOT NULL,
  `charity_cases` int(11) NOT NULL DEFAULT '0',
  `pay_cases` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`date_index`)
);
