
DROP TABLE IF EXISTS `seg_donor_info`;
CREATE TABLE `seg_donor_info` (
  `donor_id` varchar(12) NOT NULL,
  `last_name` varchar(60) DEFAULT NULL,
  `first_name` varchar(60) DEFAULT NULL,
  `middle_name` varchar(60) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `age` int(5) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `street_name` varchar(100) DEFAULT NULL,
  `brgy_nr` int(11) DEFAULT NULL,
  `mun_nr` int(11) DEFAULT NULL,
  `civil_status` varchar(35) DEFAULT NULL,
  `blood_type` enum('A','B','O','AB') DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`donor_id`)
);
