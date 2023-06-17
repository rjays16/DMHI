
DROP TABLE IF EXISTS `seg_prepaid_consultation`;
CREATE TABLE `seg_prepaid_consultation` (
  `or_no` varchar(12) NOT NULL,
  `pid` varchar(12) NOT NULL,
  `paid_consultation` tinyint(2) NOT NULL DEFAULT '0',
  `used_consultation` tinyint(2) NOT NULL DEFAULT '0',
  `history` text,
  `last_transaction_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`or_no`,`pid`),
  KEY `FK_seg_prepaid_consultation_person` (`pid`),
  CONSTRAINT `FK_seg_prepaid_consultation_or` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_prepaid_consultation_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE
);
