
DROP TABLE IF EXISTS `seg_packages_clinics`;
CREATE TABLE `seg_packages_clinics` (
  `package_id` smallint(5) unsigned NOT NULL,
  `clinic_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`package_id`,`clinic_id`),
  CONSTRAINT `FK_seg_packages_clinics` FOREIGN KEY (`package_id`) REFERENCES `seg_packages` (`package_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);
