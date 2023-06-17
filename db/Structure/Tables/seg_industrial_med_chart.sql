
DROP TABLE IF EXISTS `seg_industrial_med_chart`;
CREATE TABLE `seg_industrial_med_chart` (
  `exam_nr` int(5) unsigned NOT NULL,
  `pid` varchar(12) DEFAULT NULL,
  `encounter_nr` varchar(12) DEFAULT NULL,
  `refno` varchar(12) DEFAULT NULL,
  `diagnosis` text,
  `physician_nr` varchar(12) DEFAULT NULL,
  `recommendation` text,
  `status` text,
  `history` text,
  `create_id` varchar(100) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(100) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `remarks_other` text,
  PRIMARY KEY (`exam_nr`)
);
