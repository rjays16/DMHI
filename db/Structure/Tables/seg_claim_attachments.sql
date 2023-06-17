
DROP TABLE IF EXISTS `seg_claim_attachments`;
CREATE TABLE `seg_claim_attachments` (
  `attachment_id` int(36) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `filename` varchar(150) NOT NULL,
  `filetype` varchar(50) NOT NULL,
  `filesize` varchar(50) NOT NULL,
  `description` text,
  `url` varchar(250) DEFAULT NULL,
  `attachment_path` varchar(250) NOT NULL,
  `attachment_path_thumb` varchar(250) DEFAULT NULL,
  `document_type` varchar(10) DEFAULT NULL COMMENT 'Document to support the claim',
  `create_id` varchar(60) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `history` text,
  `is_uploaded` tinyint(1) DEFAULT '0',
  `is_deleted` tinyint(1) DEFAULT '0',
  `fileId` varchar(150) DEFAULT NULL,
  `accessUrl` varchar(250) DEFAULT NULL,
  `claimNumber` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`attachment_id`),
  KEY `FK_seg_claim_attachments_care_encounter` (`encounter_nr`),
  KEY `FK_seg_claim_attachments_doctype` (`document_type`),
  CONSTRAINT `FK_seg_claim_attachments_doctype` FOREIGN KEY (`document_type`) REFERENCES `seg_claim_doctype` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
