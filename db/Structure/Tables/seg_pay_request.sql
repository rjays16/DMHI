
DROP TABLE IF EXISTS `seg_pay_request`;
CREATE TABLE `seg_pay_request` (
  `or_no` varchar(12) NOT NULL,
  `ref_no` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD','OTHER','IC','MISC','ICB','MDC','OB') NOT NULL,
  `account_type` int(11) DEFAULT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount_due` decimal(10,4) DEFAULT NULL,
  `service_code` varchar(12) NOT NULL,
  PRIMARY KEY (`or_no`,`ref_no`,`ref_source`,`service_code`),
  KEY `idx_request` (`ref_no`,`ref_source`,`service_code`),
  KEY `orno_index` (`or_no`),
  KEY `refsource_index` (`ref_source`),
  CONSTRAINT `FK_seg_pay_request_payhdr` FOREIGN KEY (`or_no`) REFERENCES `seg_pay` (`or_no`) ON DELETE CASCADE ON UPDATE CASCADE
);
