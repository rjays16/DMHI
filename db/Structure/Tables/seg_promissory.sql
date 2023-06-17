
DROP TABLE IF EXISTS `seg_promissory`;
CREATE TABLE `seg_promissory` (
  `ref_no` varchar(12) NOT NULL,
  `note_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pid` varchar(15) NOT NULL,
  `encounter_nr` varchar(10) DEFAULT NULL,
  `guarantor` varchar(10) NOT NULL,
  `or_no` varchar(12) DEFAULT NULL,
  `history` text,
  `create_id` varchar(35) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `modify_id` varchar(35) DEFAULT NULL,
  `modify_time` datetime DEFAULT NULL,
  PRIMARY KEY (`ref_no`),
  KEY `FK_seg_promissory_guarantor` (`guarantor`),
  CONSTRAINT `FK_seg_promissory_guarantor` FOREIGN KEY (`guarantor`) REFERENCES `seg_hcare_sponsors` (`sp_id`) ON UPDATE CASCADE
);
