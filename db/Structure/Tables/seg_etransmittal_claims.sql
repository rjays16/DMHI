
DROP TABLE IF EXISTS `seg_etransmittal_claims`;
CREATE TABLE `seg_etransmittal_claims` (
  `log_id` char(36) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `claim_series_lhio` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`log_id`,`encounter_nr`),
  CONSTRAINT `FK_seg_etransmittal_claims` FOREIGN KEY (`log_id`) REFERENCES `seg_etransmittal_log` (`log_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
