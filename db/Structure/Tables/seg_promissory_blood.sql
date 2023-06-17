
DROP TABLE IF EXISTS `seg_promissory_blood`;
CREATE TABLE `seg_promissory_blood` (
  `lab_serv_refno` varchar(12) NOT NULL,
  `borrowers_name` varchar(100) DEFAULT NULL,
  `date_filed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `history` text,
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_id` varchar(35) DEFAULT NULL,
  `modified_id` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`lab_serv_refno`),
  CONSTRAINT `FK_seg_promissory_blood` FOREIGN KEY (`lab_serv_refno`) REFERENCES `seg_lab_serv` (`refno`) ON DELETE CASCADE ON UPDATE CASCADE
);
