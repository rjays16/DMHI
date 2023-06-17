
DROP TABLE IF EXISTS `seg_prescription_template_items`;
CREATE TABLE `seg_prescription_template_items` (
  `template_id` char(36) NOT NULL,
  `item_code` varchar(36) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `dosage` tinytext,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` int(10) DEFAULT NULL,
  `period_count` tinyint(4) DEFAULT NULL,
  `period_interval` enum('D','W','M') DEFAULT NULL,
  PRIMARY KEY (`template_id`,`item_code`,`item_name`),
  CONSTRAINT `FK_seg_prescription_template_items` FOREIGN KEY (`template_id`) REFERENCES `seg_prescription_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
