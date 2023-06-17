
DROP TABLE IF EXISTS `seg_pharma_prices`;
CREATE TABLE `seg_pharma_prices` (
  `bestellnum` varchar(25) NOT NULL DEFAULT '',
  `ppriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `chrgrpriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `cshrpriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bestellnum`),
  KEY `FK_seg_pharma_prices` (`modify_id`),
  KEY `FK_seg_pharma_prices_create_id` (`create_id`),
  CONSTRAINT `seg_pharma_prices_ibfk_1` FOREIGN KEY (`bestellnum`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `seg_pharma_prices_ibfk_2` FOREIGN KEY (`modify_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE,
  CONSTRAINT `seg_pharma_prices_ibfk_3` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE
);
