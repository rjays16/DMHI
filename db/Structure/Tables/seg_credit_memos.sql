
DROP TABLE IF EXISTS `seg_credit_memos`;
CREATE TABLE `seg_credit_memos` (
  `memo_nr` varchar(10) NOT NULL,
  `issue_date` datetime DEFAULT NULL,
  `memo_name` varchar(50) DEFAULT NULL,
  `memo_address` text,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `remarks` text,
  `refund_amount` decimal(10,4) DEFAULT NULL,
  `personnel` varchar(35) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`memo_nr`),
  KEY `FK_seg_credit_memos_person` (`pid`),
  CONSTRAINT `FK_seg_credit_memos_person` FOREIGN KEY (`pid`) REFERENCES `care_person` (`pid`) ON UPDATE CASCADE
);
