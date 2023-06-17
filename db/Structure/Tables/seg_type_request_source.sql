
DROP TABLE IF EXISTS `seg_type_request_source`;
CREATE TABLE `seg_type_request_source` (
  `id` varchar(20) NOT NULL,
  `source_name` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
