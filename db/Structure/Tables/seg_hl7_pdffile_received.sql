
DROP TABLE IF EXISTS `seg_hl7_pdffile_received`;
CREATE TABLE `seg_hl7_pdffile_received` (
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `filename` varchar(100) NOT NULL,
  `hl7_msg` blob NOT NULL,
  PRIMARY KEY (`filename`),
  KEY `date_received` (`date_received`)
);
