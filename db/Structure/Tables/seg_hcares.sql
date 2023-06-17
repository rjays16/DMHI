
DROP TABLE IF EXISTS `seg_hcares`;
CREATE TABLE `seg_hcares` (
  `hcare_id` int(8) unsigned NOT NULL,
  `hcare_desc` varchar(80) NOT NULL,
  `hcare_company` varchar(80) NOT NULL,
  `hcare_contact_person` varchar(80) NOT NULL,
  `hcare_addr1` varchar(40) NOT NULL,
  `hcare_addr2` varchar(40) NOT NULL,
  `hcare_contact_no` varchar(20) NOT NULL,
  PRIMARY KEY (`hcare_id`)
);
