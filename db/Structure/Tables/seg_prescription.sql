
DROP TABLE IF EXISTS `seg_prescription`;
CREATE TABLE `seg_prescription` (
  `id` char(36) NOT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `prescription_date` date DEFAULT NULL,
  `instructions` text,
  `clinical_impression` text,
  `is_deleted` tinyint(1) DEFAULT '0',
  `reason_for_deletion` tinytext,
  `history` tinytext,
  `create_id` varchar(36) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(36) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);
