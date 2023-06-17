
DROP TABLE IF EXISTS `seg_credit_memo_details`;
CREATE TABLE `seg_credit_memo_details` (
  `memo_nr` varchar(10) NOT NULL,
  `or_no` varchar(12) NOT NULL,
  `ref_source` enum('PH','LD','RD','OR','OTHER','FB','PP') NOT NULL,
  `ref_no` varchar(10) NOT NULL,
  `service_code` varchar(12) NOT NULL DEFAULT '',
  `service_name` varchar(40) DEFAULT NULL,
  `service_desc` varchar(200) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`memo_nr`,`or_no`,`ref_source`,`ref_no`,`service_code`),
  KEY `FK_seg_credit_memo_details_pay` (`or_no`),
  CONSTRAINT `FK_seg_credit_memo_details` FOREIGN KEY (`memo_nr`) REFERENCES `seg_credit_memos` (`memo_nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_credit_memo_details_pay` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE NO ACTION ON UPDATE CASCADE
);
