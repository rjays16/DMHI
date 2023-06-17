
DROP TABLE IF EXISTS `seg_etransmittal_voucher_inprocess`;
CREATE TABLE `seg_etransmittal_voucher_inprocess` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `voucher_no` varchar(20) NOT NULL,
  `deficiency` varchar(5) DEFAULT NULL,
  `requirements` text,
  PRIMARY KEY (`claim_series_lhio`,`voucher_no`)
);
