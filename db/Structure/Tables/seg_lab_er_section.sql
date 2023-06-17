
DROP TABLE IF EXISTS `seg_lab_er_section`;
CREATE TABLE `seg_lab_er_section` (
  `group_code` varchar(10) NOT NULL,
  PRIMARY KEY (`group_code`),
  CONSTRAINT `FK_seg_lab_er_section` FOREIGN KEY (`group_code`) REFERENCES `seg_lab_service_groups` (`group_code`) ON DELETE NO ACTION ON UPDATE CASCADE
);
