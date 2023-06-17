
DROP TABLE IF EXISTS `seg_hcare_packages`;
CREATE TABLE `seg_hcare_packages` (
  `bsked_id` int(11) unsigned NOT NULL,
  `package_id` smallint(5) unsigned NOT NULL,
  `amountlimit` double(10,4) NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`bsked_id`,`package_id`),
  KEY `FK_seg_hcare_packages_pkgs` (`package_id`),
  CONSTRAINT `FK_seg_hcare_packages_bsked` FOREIGN KEY (`bsked_id`) REFERENCES `seg_hcare_bsked` (`bsked_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_hcare_packages_pkgs` FOREIGN KEY (`package_id`) REFERENCES `seg_packages` (`package_id`) ON UPDATE CASCADE
);
