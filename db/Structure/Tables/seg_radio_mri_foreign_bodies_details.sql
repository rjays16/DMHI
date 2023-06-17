
DROP TABLE IF EXISTS `seg_radio_mri_foreign_bodies_details`;
CREATE TABLE `seg_radio_mri_foreign_bodies_details` (
  `refno` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
