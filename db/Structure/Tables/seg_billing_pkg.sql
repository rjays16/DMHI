
DROP TABLE IF EXISTS `seg_billing_pkg`;
CREATE TABLE `seg_billing_pkg` (
  `ref_no` varchar(12) NOT NULL,
  `package_id` smallint(5) unsigned NOT NULL,
  `is_freedist` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ref_no`,`package_id`),
  KEY `FK_seg_billing_pkg_packages` (`package_id`),
  CONSTRAINT `FK_seg_billing_pkg_packages` FOREIGN KEY (`package_id`) REFERENCES `seg_packages` (`package_id`) ON UPDATE CASCADE
);
