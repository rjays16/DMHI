
DROP TABLE IF EXISTS `seg_sponsor_ledger`;
CREATE TABLE `seg_sponsor_ledger` (
  `sp_id` varchar(10) NOT NULL,
  `entry_dte` datetime NOT NULL,
  `refno` varchar(12) NOT NULL,
  `ref_type` enum('RD','PP','PH','OR','MD','LD','FB','RE') NOT NULL,
  `debit` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `credit` decimal(20,4) NOT NULL,
  PRIMARY KEY (`sp_id`,`entry_dte`,`refno`,`ref_type`),
  CONSTRAINT `FK_seg_sponsor_ledger_sponsor` FOREIGN KEY (`sp_id`) REFERENCES `seg_hcare_sponsors` (`sp_id`) ON UPDATE CASCADE
);
