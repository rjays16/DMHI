
DROP TABLE IF EXISTS `seg_dialysis_package_details`;
CREATE TABLE `seg_dialysis_package_details` (
  `id` smallint(5) unsigned NOT NULL,
  `item_code` varchar(12) NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `source` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`,`item_code`),
  CONSTRAINT `FK_seg_dialysis_package_details` FOREIGN KEY (`id`) REFERENCES `seg_dialysis_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
