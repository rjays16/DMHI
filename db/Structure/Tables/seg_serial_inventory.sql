
DROP TABLE IF EXISTS `seg_serial_inventory`;
CREATE TABLE `seg_serial_inventory` (
  `serial_no` varchar(25) NOT NULL,
  `area_code` varchar(10) DEFAULT NULL,
  `property_no` varchar(40) DEFAULT NULL,
  `acquisition_cost` decimal(10,4) DEFAULT '0.0000',
  `acquisition_date` date DEFAULT '0000-00-00',
  `supplier_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`serial_no`)
);
