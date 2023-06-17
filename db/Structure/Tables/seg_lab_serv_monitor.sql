
DROP TABLE IF EXISTS `seg_lab_serv_monitor`;
CREATE TABLE `seg_lab_serv_monitor` (
  `refno` varchar(12) NOT NULL,
  `service_code` varchar(10) NOT NULL,
  `every_hour` int(3) DEFAULT NULL,
  `no_takes` int(3) DEFAULT '1',
  `status` enum('stop','continue') DEFAULT 'continue',
  PRIMARY KEY (`refno`,`service_code`),
  CONSTRAINT `FK_seg_lab_serv_monitor` FOREIGN KEY (`refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
