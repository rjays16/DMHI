
DROP TABLE IF EXISTS `seg_claim_doctype`;
CREATE TABLE `seg_claim_doctype` (
  `id` varchar(10) NOT NULL,
  `document_type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
