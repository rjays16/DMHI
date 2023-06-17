
DROP TABLE IF EXISTS `seg_person_name_history`;
CREATE TABLE `seg_person_name_history` (
  `hrn` varchar(12) DEFAULT NULL,
  `old_fname` varchar(60) DEFAULT NULL,
  `old_lname` varchar(60) DEFAULT NULL,
  `old_mname` varchar(60) DEFAULT NULL,
  `create_id` varchar(60) DEFAULT NULL
);
