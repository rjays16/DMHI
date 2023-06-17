
DROP TABLE IF EXISTS `seg_hospital_service_price`;
CREATE TABLE `seg_hospital_service_price` (
  `refno` varchar(12) NOT NULL,
  `effectivity_date` date DEFAULT '0000-00-00',
  `history` text,
  `create_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `create_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT '0000-00-00 00:00:00',
  `modify_id` varchar(35) NOT NULL,
  PRIMARY KEY (`refno`)
);
