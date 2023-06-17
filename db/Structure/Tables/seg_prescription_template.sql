
DROP TABLE IF EXISTS `seg_prescription_template`;
CREATE TABLE `seg_prescription_template` (
  `id` char(36) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `owner` varchar(36) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `history` tinytext,
  `create_id` varchar(36) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(36) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
