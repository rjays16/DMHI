
DROP TABLE IF EXISTS `seg_lab_er_test`;
CREATE TABLE `seg_lab_er_test` (
  `service_code` varchar(10) NOT NULL,
  PRIMARY KEY (`service_code`),
  CONSTRAINT `FK_seg_lab_er_test` FOREIGN KEY (`service_code`) REFERENCES `seg_lab_services` (`service_code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
