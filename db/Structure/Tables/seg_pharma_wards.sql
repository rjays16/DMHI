
DROP TABLE IF EXISTS `seg_pharma_wards`;
CREATE TABLE `seg_pharma_wards` (
  `ward_id` int(11) NOT NULL,
  `ward_name` varchar(40) DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`ward_id`)
);
