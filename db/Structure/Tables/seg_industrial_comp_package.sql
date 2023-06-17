
DROP TABLE IF EXISTS `seg_industrial_comp_package`;
CREATE TABLE `seg_industrial_comp_package` (
  `company_id` varchar(12) NOT NULL,
  `package_id` char(36) NOT NULL,
  `price` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`company_id`,`package_id`),
  KEY `FK_seg_industrial_comppackage_2` (`package_id`),
  CONSTRAINT `FK_seg_industrial_comppackage` FOREIGN KEY (`company_id`) REFERENCES `seg_industrial_company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_industrial_comppackage_2` FOREIGN KEY (`package_id`) REFERENCES `seg_industrial_package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
