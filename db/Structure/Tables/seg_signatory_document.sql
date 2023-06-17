
DROP TABLE IF EXISTS `seg_signatory_document`;
CREATE TABLE `seg_signatory_document` (
  `document_code` varchar(20) NOT NULL,
  `document_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`document_code`)
);
