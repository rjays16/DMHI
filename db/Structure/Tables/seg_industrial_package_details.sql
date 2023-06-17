
DROP TABLE IF EXISTS `seg_industrial_package_details`;
CREATE TABLE `seg_industrial_package_details` (
  `package_id` char(36) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `service_area` enum('RD','LB','MD','PH','BB','SPL','OT') NOT NULL,
  PRIMARY KEY (`package_id`,`service_code`,`service_area`),
  CONSTRAINT `FK_seg_industrial_pack_details` FOREIGN KEY (`package_id`) REFERENCES `seg_industrial_package` (`package_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
