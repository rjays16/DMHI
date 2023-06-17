
DROP TABLE IF EXISTS `seg_override_amount`;
CREATE TABLE `seg_override_amount` (
  `ref_no` varchar(12) NOT NULL,
  `ref_source` enum('PP','FB','LD','RD','OR','PH','MD') NOT NULL,
  `grant_dte` datetime NOT NULL,
  `personnel_nr` int(11) NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  KEY `FK_seg_charity_amount_personell` (`personnel_nr`)
);
