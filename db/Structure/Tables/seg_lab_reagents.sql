
DROP TABLE IF EXISTS `seg_lab_reagents`;
CREATE TABLE `seg_lab_reagents` (
  `reagent_code` varchar(12) NOT NULL,
  `reagent_name` varchar(100) DEFAULT NULL,
  `other_name` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`reagent_code`)
);
