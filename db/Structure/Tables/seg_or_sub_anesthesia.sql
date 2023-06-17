
DROP TABLE IF EXISTS `seg_or_sub_anesthesia`;
CREATE TABLE `seg_or_sub_anesthesia` (
  `nr` smallint(2) unsigned NOT NULL,
  `anesthesia_id` varchar(35) DEFAULT NULL,
  `sub_anesth_id` varchar(35) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`nr`),
  KEY `FK_seg_or_sub_anesthesia` (`anesthesia_id`),
  CONSTRAINT `FK_seg_or_sub_anesthesia` FOREIGN KEY (`anesthesia_id`) REFERENCES `care_type_anaesthesia` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
