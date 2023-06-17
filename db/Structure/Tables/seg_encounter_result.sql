
DROP TABLE IF EXISTS `seg_encounter_result`;
CREATE TABLE `seg_encounter_result` (
  `encounter_nr` varchar(12) NOT NULL,
  `result_code` int(11) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_time` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`encounter_nr`,`result_code`)
);
