
DROP TABLE IF EXISTS `seg_sponsor_deposit`;
CREATE TABLE `seg_sponsor_deposit` (
  `dep_no` varchar(12) NOT NULL,
  `dep_dte` datetime NOT NULL,
  `dep_amnt` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `sp_id` varchar(10) NOT NULL,
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`dep_no`),
  KEY `FK_seg_sponsor_deposit_sponsor` (`sp_id`),
  CONSTRAINT `FK_seg_sponsor_deposit_sponsor` FOREIGN KEY (`sp_id`) REFERENCES `seg_hcare_sponsors` (`sp_id`) ON UPDATE CASCADE
);
