
DROP TABLE IF EXISTS `seg_other_hospital`;
CREATE TABLE `seg_other_hospital` (
  `id` int(11) NOT NULL,
  `hosp_name` varchar(200) DEFAULT NULL,
  `hosp_address` varchar(300) DEFAULT NULL,
  `contact_no` varchar(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
