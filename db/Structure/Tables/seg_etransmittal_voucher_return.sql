
DROP TABLE IF EXISTS `seg_etransmittal_voucher_return`;
CREATE TABLE `seg_etransmittal_voucher_return` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `voucher_no` varchar(20) NOT NULL,
  `process_stage` enum('validation','editing','editing_receiving','encoding','receiving') DEFAULT NULL,
  `process_date` date DEFAULT NULL,
  PRIMARY KEY (`claim_series_lhio`,`voucher_no`)
);
