
DROP TABLE IF EXISTS `seg_signatory_outside`;
CREATE TABLE `seg_signatory_outside` (
  `id` int(11) unsigned NOT NULL,
  `signatory_name` varchar(200) DEFAULT NULL,
  `signatory_position` varchar(100) DEFAULT NULL,
  `signatory_title` varchar(100) DEFAULT NULL,
  `document_code` varchar(20) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`,`document_code`),
  KEY `FK_seg_signatory` (`document_code`)
);
