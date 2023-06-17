
DROP TABLE IF EXISTS `seg_promissory_details`;
CREATE TABLE `seg_promissory_details` (
  `ref_no` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD','OTHER','PROM','') NOT NULL,
  `service_code` varchar(12) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `amount_item` decimal(10,2) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ref_no`,`ref_source`,`service_code`),
  CONSTRAINT `FK_seg_promissory_details` FOREIGN KEY (`ref_no`) REFERENCES `seg_promissory` (`ref_no`) ON UPDATE CASCADE
);
