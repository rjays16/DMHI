
DROP TABLE IF EXISTS `seg_additional_insurance`;
CREATE TABLE `seg_additional_insurance` (
  `pid` varchar(12) NOT NULL,
  `hcare_id` int(8) NOT NULL,
  `service_type` varchar(25) NOT NULL,
  `amount` double(20,4) NOT NULL DEFAULT '0.0000'
);
