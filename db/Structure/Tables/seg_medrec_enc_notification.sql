
DROP TABLE IF EXISTS `seg_medrec_enc_notification`;
CREATE TABLE `seg_medrec_enc_notification` (
  `encounter_nr` varchar(12) NOT NULL,
  `notification_id` varchar(50) NOT NULL,
  `date_requested` date DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `history` varchar(100) DEFAULT NULL,
  `create_id` varchar(60) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(60) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`encounter_nr`,`notification_id`),
  KEY `FK_seg_medrec_enc_notification` (`notification_id`),
  CONSTRAINT `FK_seg_medrec_enc_notification` FOREIGN KEY (`notification_id`) REFERENCES `seg_medrec_notifications` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
