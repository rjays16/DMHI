
DROP TABLE IF EXISTS `seg_social_severity_index`;
CREATE TABLE `seg_social_severity_index` (
  `severity_nr` int(11) NOT NULL,
  `severity_index` varchar(50) NOT NULL,
  PRIMARY KEY (`severity_nr`)
);
