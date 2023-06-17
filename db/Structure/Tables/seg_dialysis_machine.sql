
DROP TABLE IF EXISTS `seg_dialysis_machine`;
CREATE TABLE `seg_dialysis_machine` (
  `id` char(36) NOT NULL,
  `machine_no` smallint(5) DEFAULT NULL,
  `serial_no` varchar(30) DEFAULT NULL,
  `create_id` varchar(12) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
