
DROP TABLE IF EXISTS `seg_signatory`;
CREATE TABLE `seg_signatory` (
  `personell_nr` int(11) unsigned NOT NULL,
  `signatory_position` varchar(100) DEFAULT NULL,
  `signatory_title` varchar(100) DEFAULT NULL,
  `document_code` varchar(20) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`personell_nr`,`document_code`),
  KEY `FK_seg_signatory` (`document_code`),
  CONSTRAINT `FK_seg_signatory` FOREIGN KEY (`document_code`) REFERENCES `seg_signatory_document` (`document_code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
