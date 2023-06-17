
DROP TABLE IF EXISTS `seg_issuance`;
CREATE TABLE `seg_issuance` (
  `refno` varchar(12) NOT NULL,
  `issue_date` datetime DEFAULT '0000-00-00 00:00:00',
  `src_area_code` varchar(10) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `authorizing_id` int(11) DEFAULT NULL,
  `issuing_id` int(11) DEFAULT NULL,
  `issue_type` varchar(15) DEFAULT 'ST',
  `acknowledging_id` int(11) DEFAULT NULL,
  `acknowledge_date` datetime DEFAULT '0000-00-00 00:00:00',
  `status` varchar(25) DEFAULT 'ISSUED',
  PRIMARY KEY (`refno`),
  KEY `FK_seg_issuance_personell` (`authorizing_id`),
  KEY `FK_seg_issuance_personell2` (`issuing_id`),
  KEY `FK_seg_issuance_department` (`area_code`),
  CONSTRAINT `FK_seg_issuance_personell` FOREIGN KEY (`authorizing_id`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_issuance_personell2` FOREIGN KEY (`issuing_id`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
