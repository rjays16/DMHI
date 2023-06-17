
DROP TABLE IF EXISTS `seg_internal_request`;
CREATE TABLE `seg_internal_request` (
  `refno` varchar(12) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `requestor_id` int(11) NOT NULL,
  `area_code` varchar(10) NOT NULL,
  `area_code_dest` varchar(10) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'APPROVED',
  PRIMARY KEY (`refno`),
  KEY `dept_index` (`area_code`),
  KEY `FK_seg_internal_request_personell` (`requestor_id`),
  CONSTRAINT `FK_seg_internal_request_personell` FOREIGN KEY (`requestor_id`) REFERENCES `care_personell` (`nr`) ON UPDATE CASCADE
);
