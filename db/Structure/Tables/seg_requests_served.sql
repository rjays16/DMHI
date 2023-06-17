
DROP TABLE IF EXISTS `seg_requests_served`;
CREATE TABLE `seg_requests_served` (
  `request_refno` varchar(25) NOT NULL,
  `issue_refno` varchar(25) NOT NULL,
  `item_code` varchar(25) NOT NULL,
  `served_qty` double DEFAULT '0',
  PRIMARY KEY (`request_refno`,`issue_refno`,`item_code`),
  KEY `FK_seg_requests_served_care_pharma_products_main(VARCHAR)` (`item_code`),
  KEY `FK_seg_requests_served_seg_issuance_details(VARCHAR)` (`issue_refno`),
  CONSTRAINT `FK_seg_requests_served_care_pharma_products_main(VARCHAR)` FOREIGN KEY (`item_code`) REFERENCES `care_pharma_products_main` (`bestellnum`) ON UPDATE CASCADE
);
