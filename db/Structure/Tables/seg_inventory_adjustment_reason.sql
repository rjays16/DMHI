
DROP TABLE IF EXISTS `seg_inventory_adjustment_reason`;
CREATE TABLE `seg_inventory_adjustment_reason` (
  `adj_reason_id` varchar(10) NOT NULL,
  `adj_reason_name` varchar(50) NOT NULL,
  PRIMARY KEY (`adj_reason_id`)
);
