
DROP TABLE IF EXISTS `seg_or_type`;
CREATE TABLE `seg_or_type` (
  `or_type_acro` varchar(15) NOT NULL,
  `or_type_description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`or_type_acro`)
);
