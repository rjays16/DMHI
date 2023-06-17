
DROP TABLE IF EXISTS `seg_ortho_docs`;
CREATE TABLE `seg_ortho_docs` (
  `id` int(4) NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `surg_doc_dr` int(6) DEFAULT NULL,
  `surg_doc_section` int(6) DEFAULT NULL,
  `surg_doc_role` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
