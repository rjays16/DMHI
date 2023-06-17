
DROP TABLE IF EXISTS `seg_radio_services_excluded`;
CREATE TABLE `seg_radio_services_excluded` (
  `id` int(10) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`,`service_code`),
  KEY `FK_service_code_excluded` (`service_code`),
  CONSTRAINT `FK_service_code_excluded` FOREIGN KEY (`service_code`) REFERENCES `seg_radio_services` (`service_code`) ON DELETE CASCADE ON UPDATE CASCADE
);
