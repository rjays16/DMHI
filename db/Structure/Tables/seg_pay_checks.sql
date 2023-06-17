
DROP TABLE IF EXISTS `seg_pay_checks`;
CREATE TABLE `seg_pay_checks` (
  `or_no` varchar(12) NOT NULL,
  `check_no` varchar(12) NOT NULL,
  `check_date` date DEFAULT NULL,
  `bank_name` varchar(200) DEFAULT NULL,
  `payee` varchar(200) DEFAULT NULL,
  `amount` decimal(10,4) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`or_no`,`check_no`),
  KEY `FK_company_id` (`company_name`),
  CONSTRAINT `FK_seg_pay_checks` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE
);
