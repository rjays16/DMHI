
DROP TABLE IF EXISTS `seg_sponsor_amount`;
CREATE TABLE `seg_sponsor_amount` (
  `refno` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD') NOT NULL,
  `sp_id` varchar(10) NOT NULL,
  `control_nr` varchar(12) NOT NULL,
  `grant_dte` datetime NOT NULL,
  `granter_id` varchar(35) NOT NULL,
  `sp_amount` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `modify_id` varchar(35) NOT NULL,
  `modify_dt` datetime DEFAULT NULL,
  `create_id` varchar(35) NOT NULL,
  `create_dt` datetime DEFAULT NULL,
  PRIMARY KEY (`refno`,`ref_source`,`sp_id`),
  KEY `FK_seg_sponsor_amount_sponsor` (`sp_id`),
  KEY `FK_seg_sponsor_amount_user` (`granter_id`),
  CONSTRAINT `FK_seg_sponsor_amount_sponsor` FOREIGN KEY (`sp_id`) REFERENCES `seg_hcare_sponsors` (`sp_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_seg_sponsor_amount_user` FOREIGN KEY (`granter_id`) REFERENCES `care_users` (`login_id`) ON UPDATE CASCADE
);
