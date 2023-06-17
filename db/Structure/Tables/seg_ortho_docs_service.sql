
DROP TABLE IF EXISTS `seg_ortho_docs_service`;
CREATE TABLE `seg_ortho_docs_service` (
  `id` int(2) NOT NULL,
  `service_color` varchar(12) DEFAULT NULL,
  `specialty_id` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
