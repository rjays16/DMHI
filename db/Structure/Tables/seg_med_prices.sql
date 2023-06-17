
DROP TABLE IF EXISTS `seg_med_prices`;
CREATE TABLE `seg_med_prices` (
  `bestellnum` varchar(25) NOT NULL,
  `ppriceppk` decimal(20,4) NOT NULL,
  `chrgrpriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `cshrpriceppk` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`bestellnum`),
  KEY `FK_seg_med_prices_1` (`create_id`),
  KEY `FK_seg_med_prices_2` (`modify_id`),
  CONSTRAINT `seg_med_prices_ibfk_1` FOREIGN KEY (`bestellnum`) REFERENCES `care_med_products_main` (`bestellnum`) ON UPDATE CASCADE,
  CONSTRAINT `seg_med_prices_ibfk_2` FOREIGN KEY (`create_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE
);
