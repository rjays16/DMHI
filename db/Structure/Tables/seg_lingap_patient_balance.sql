
DROP TABLE IF EXISTS `seg_lingap_patient_balance`;
CREATE TABLE `seg_lingap_patient_balance` (
  `pid` varchar(12) NOT NULL,
  `running_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pid`)
);
