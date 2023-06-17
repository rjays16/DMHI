
DROP TABLE IF EXISTS `code_national`;
CREATE TABLE `code_national` (
  `national` varchar(25) NOT NULL,
  `code_natio` int(11) NOT NULL,
  `nationality` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`code_natio`)
);
