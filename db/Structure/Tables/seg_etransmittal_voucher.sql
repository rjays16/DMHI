
DROP TABLE IF EXISTS `seg_etransmittal_voucher`;
CREATE TABLE `seg_etransmittal_voucher` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `voucher_no` varchar(20) NOT NULL,
  `voucher_date` varchar(20) DEFAULT NULL,
  `check_no` varchar(20) DEFAULT NULL,
  `check_date` varchar(20) DEFAULT NULL,
  `check_amount` decimal(10,2) DEFAULT NULL,
  `claim_amount` decimal(10,2) DEFAULT NULL,
  `payee` varchar(200) NOT NULL,
  PRIMARY KEY (`claim_series_lhio`,`voucher_no`,`payee`)
);
