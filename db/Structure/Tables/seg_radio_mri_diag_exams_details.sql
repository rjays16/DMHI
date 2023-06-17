
DROP TABLE IF EXISTS `seg_radio_mri_diag_exams_details`;
CREATE TABLE `seg_radio_mri_diag_exams_details` (
  `refno` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `details` varchar(250) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `id` int(10) DEFAULT NULL,
  PRIMARY KEY (`refno`)
);
