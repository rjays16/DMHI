
DROP TABLE IF EXISTS `seg_industrial_bill_d`;
CREATE TABLE `seg_industrial_bill_d` (
  `bill_nr` varchar(12) NOT NULL,
  `encounter_nr` varchar(12) NOT NULL,
  `total_med_charge` double(10,4) NOT NULL,
  `total_sup_charge` double(10,4) NOT NULL,
  `total_srv_charge` double(10,4) NOT NULL,
  `total_msc_charge` double(10,4) NOT NULL,
  PRIMARY KEY (`bill_nr`,`encounter_nr`),
  CONSTRAINT `FK_seg_industrial_bill_d_h` FOREIGN KEY (`bill_nr`) REFERENCES `seg_industrial_bill_h` (`bill_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
