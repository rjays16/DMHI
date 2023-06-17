
DROP TABLE IF EXISTS `seg_package_details`;
CREATE TABLE `seg_package_details` (
  `package_id` smallint(5) unsigned NOT NULL,
  `item_id` varchar(25) NOT NULL,
  `item_purpose` enum('PH','LB','RD','MISC') DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `area` varchar(10) DEFAULT NULL,
  `item_type` varchar(10) DEFAULT NULL,
  `unit` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`package_id`,`item_id`),
  CONSTRAINT `FK_seg_package_details` FOREIGN KEY (`package_id`) REFERENCES `seg_packages` (`package_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);
