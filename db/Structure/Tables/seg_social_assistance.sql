
DROP TABLE IF EXISTS `seg_social_assistance`;
CREATE TABLE `seg_social_assistance` (
  `encounter_nr` varchar(12) NOT NULL,
  `dswd_amount` double(10,2) DEFAULT NULL,
  `cmap_amount` double(10,2) DEFAULT NULL,
  `lingap_amount` double(10,2) DEFAULT NULL,
  `pdaf_amount` double(10,2) DEFAULT NULL,
  `pcso_amount` double(10,2) DEFAULT NULL,
  `pn_amount` double(10,2) DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`encounter_nr`)
);
