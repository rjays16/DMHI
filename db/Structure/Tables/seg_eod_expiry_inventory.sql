
DROP TABLE IF EXISTS `seg_eod_expiry_inventory`;
CREATE TABLE `seg_eod_expiry_inventory` (
  `item_code` varchar(25) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `eod_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `eod_qty` double NOT NULL,
  PRIMARY KEY (`item_code`,`area_code`,`eod_date`,`expiry_date`)
);
