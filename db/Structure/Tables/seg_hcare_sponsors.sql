
DROP TABLE IF EXISTS `seg_hcare_sponsors`;
CREATE TABLE `seg_hcare_sponsors` (
  `sp_id` varchar(10) NOT NULL,
  `sp_name` varchar(100) NOT NULL,
  `sp_addr1` varchar(80) NOT NULL,
  `sp_addr2` varchar(80) NOT NULL,
  `sp_telno` varchar(20) NOT NULL,
  `sp_contactperson` varchar(80) NOT NULL,
  PRIMARY KEY (`sp_id`)
);
