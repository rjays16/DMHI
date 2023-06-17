
DROP TABLE IF EXISTS `package_details`;
CREATE TABLE `package_details` (
  `package_id` smallint(5) unsigned NOT NULL,
  `item_id` varchar(15) DEFAULT NULL,
  `item_purpose` varchar(30) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `unit` varchar(15) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `area` varchar(10) DEFAULT NULL,
  `item_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`package_id`)
);
