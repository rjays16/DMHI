
DROP TABLE IF EXISTS `seg_radio_doctor_member`;
CREATE TABLE `seg_radio_doctor_member` (
  `group_nr` int(11) DEFAULT NULL,
  `doctor_member` int(11) DEFAULT NULL,
  KEY `FK_seg_radio_doctor_member` (`group_nr`),
  CONSTRAINT `FK_seg_radio_doctor_member` FOREIGN KEY (`group_nr`) REFERENCES `seg_radio_doctor_group` (`group_nr`) ON DELETE CASCADE ON UPDATE CASCADE
);
