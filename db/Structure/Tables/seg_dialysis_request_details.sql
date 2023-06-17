
DROP TABLE IF EXISTS `seg_dialysis_request_details`;
CREATE TABLE `seg_dialysis_request_details` (
  `refno` varchar(12) NOT NULL,
  `package_id` smallint(5) unsigned NOT NULL,
  `quantity` double(10,2) DEFAULT NULL,
  `amount` double(10,4) DEFAULT NULL,
  `request_flag` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`refno`,`package_id`),
  KEY `FK_seg_dialysis_request_details_flag` (`request_flag`),
  KEY `FK_seg_dialysis_request_details_package` (`package_id`),
  CONSTRAINT `FK_seg_dialysis_request_details` FOREIGN KEY (`refno`) REFERENCES `seg_dialysis_request` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_dialysis_request_details_flag` FOREIGN KEY (`request_flag`) REFERENCES `seg_type_charge` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_dialysis_request_details_package` FOREIGN KEY (`package_id`) REFERENCES `seg_dialysis_package` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
