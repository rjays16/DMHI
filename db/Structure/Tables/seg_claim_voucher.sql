
DROP TABLE IF EXISTS `seg_claim_voucher`;
CREATE TABLE `seg_claim_voucher` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `voucher_no` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`claim_series_lhio`)
);
