
DROP TABLE IF EXISTS `seg_prescription_items`;
CREATE TABLE `seg_prescription_items` (
  `prescription_id` char(36) NOT NULL,
  `item_code` varchar(36) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` decimal(10,4) DEFAULT NULL,
  `unit` int(10) DEFAULT NULL,
  `dosage` tinytext,
  `period_count` tinyint(4) DEFAULT NULL,
  `period_interval` enum('D','W','M') DEFAULT NULL,
  PRIMARY KEY (`prescription_id`,`item_code`,`item_name`),
  CONSTRAINT `FK_seg_prescription_items` FOREIGN KEY (`prescription_id`) REFERENCES `seg_prescription` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
