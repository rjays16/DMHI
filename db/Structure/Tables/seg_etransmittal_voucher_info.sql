
DROP TABLE IF EXISTS `seg_etransmittal_voucher_info`;
CREATE TABLE `seg_etransmittal_voucher_info` (
  `claim_series_lhio` varchar(15) NOT NULL,
  `phic_nr` varchar(25) NOT NULL,
  `claim_received` date DEFAULT NULL,
  `claim_refile` date DEFAULT NULL,
  `status` enum('in_process','return','denied','with_check','with_voucher','vouchering') DEFAULT NULL,
  PRIMARY KEY (`claim_series_lhio`,`phic_nr`)
);
