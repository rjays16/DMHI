
DROP TABLE IF EXISTS `seg_radio_mri_tso_fbodies_details`;
CREATE TABLE `seg_radio_mri_tso_fbodies_details` (
  `refno` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `details` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
