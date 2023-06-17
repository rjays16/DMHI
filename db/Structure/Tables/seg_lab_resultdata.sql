
DROP TABLE IF EXISTS `seg_lab_resultdata`;
CREATE TABLE `seg_lab_resultdata` (
  `refno` varchar(12) NOT NULL,
  `group_id` smallint(5) NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `service_date` datetime NOT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `pathologist_pid` varchar(12) NOT NULL,
  `med_tech_pid` varchar(12) NOT NULL,
  `status` varchar(15) NOT NULL,
  `cancel_reason` varchar(100) DEFAULT NULL,
  `is_confidential` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`refno`,`group_id`,`service_code`,`status`)
);
