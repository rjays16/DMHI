
DROP TABLE IF EXISTS `seg_service_area`;
CREATE TABLE `seg_service_area` (
  `area_code` varchar(10) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`area_code`)
);
