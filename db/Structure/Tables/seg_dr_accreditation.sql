
DROP TABLE IF EXISTS `seg_dr_accreditation`;
CREATE TABLE `seg_dr_accreditation` (
  `dr_nr` int(11) NOT NULL,
  `hcare_id` int(8) NOT NULL,
  `accreditation_nr` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `history` text,
  `modify_id` varchar(300) DEFAULT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(300) DEFAULT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`dr_nr`,`hcare_id`),
  CONSTRAINT `FK_seg_dr_accreditation_personell` FOREIGN KEY (`dr_nr`) REFERENCES `care_personell` (`nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
