
DROP TABLE IF EXISTS `seg_donor_transaction`;
CREATE TABLE `seg_donor_transaction` (
  `donor_id` varchar(12) NOT NULL,
  `item_id` int(10) NOT NULL,
  `donor_date` date DEFAULT NULL,
  `donor_time` time DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `unit` varchar(35) DEFAULT NULL,
  `create_id` varchar(35) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `status` varchar(35) DEFAULT NULL,
  UNIQUE KEY `item_id` (`item_id`)
);
