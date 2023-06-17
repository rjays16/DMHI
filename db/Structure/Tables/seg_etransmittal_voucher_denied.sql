
DROP TABLE IF EXISTS `seg_etransmittal_voucher_denied`;
CREATE TABLE `seg_etransmittal_voucher_denied` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `voucher_no` varchar(20) NOT NULL,
  `reason` text,
  PRIMARY KEY (`claim_series_lhio`,`voucher_no`)
);
