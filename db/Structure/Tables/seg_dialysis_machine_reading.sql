
DROP TABLE IF EXISTS `seg_dialysis_machine_reading`;
CREATE TABLE `seg_dialysis_machine_reading` (
  `id` char(36) NOT NULL,
  `machine_id` char(36) DEFAULT NULL,
  `final_reading` decimal(10,4) DEFAULT NULL,
  `reading_date` date DEFAULT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_seg_dialysis_machine_reading` (`machine_id`),
  CONSTRAINT `FK_seg_dialysis_machine_reading` FOREIGN KEY (`machine_id`) REFERENCES `seg_dialysis_machine` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);
